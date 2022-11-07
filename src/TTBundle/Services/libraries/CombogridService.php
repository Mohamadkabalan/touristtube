<?php
namespace TTBundle\Services\libraries;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use TTBundle\Model\TTSearchCritiria;
use TTBundle\Utils;

class CombogridService
{

    protected $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    public static function prepareCriteria(Request $request)
    {
        $rows = $request->request->get('rows');
        $currPage = $request->request->get('currPage');
        $sortOrder = $request->request->get('sortOrder');
        $searchTerm = $request->request->get('searchTerm');
        $excluded = $request->request->get('excluded');
        $page = isset($currPage) ? $currPage : 0; // get the requested page
        $limit = isset($rows) ? $rows : 10; // get how many rows we want to have into the grid

        $sortOrder = isset($sortOrder) ? $sortOrder : "";
        $searchTerm = isset($searchTerm) ? $searchTerm : "";

        $tt_search_critiria_obj = new TTSearchCritiria();
        $tt_search_critiria_obj->setTerm($searchTerm);
        $tt_search_critiria_obj->setLimit($limit);
        $tt_search_critiria_obj->setPage($page);
        $tt_search_critiria_obj->setSortOrder($sortOrder);
        $tt_search_critiria_obj->setExcluded($excluded);
        //
        //
        $start = ($limit * $page);
        if ($start < 0)
            $start = 0;
        $tt_search_critiria_obj->setStart($start);
        
        //
        return $tt_search_critiria_obj;
    }

    /**
     * list [Array] : List of retrieved rows from DB
     * idProperty [String] : The name of the id property to be retrieved from the list records
     * property [String] : The name of the label property to be retrieved from the list records
     * totalRec [int] : The total number of records that can be selected from DB for the given search criteria
     * request [HTTP Request]
     */
    public static function renderDropDownComboGrid($list = array(), $totalRec = 0, $idProperty = null, $property = null, $request)
    {
        $returnColModel = false;
        $colModel = $request->request->get("colModel");
        $jsonModel = array();

        if (! isset($colModel) || $colModel == "") {
            $columnLabel = $request->request->get("columnLabel");
            if (! isset($columnLabel) || $columnLabel == "")
                $columnLabel = " ";
            else
                $columnLabel = urldecode($columnLabel);

            if (isset($idProperty) && $idProperty != "") {
                $colRec = array();
                $colRec["columnName"] = $idProperty;
                $colRec["width"] = 30;
                $colRec["label"] = "Id";
                $colRec["hidden"] = true;
                $colRec["isIdProperty"] = true;
                
                $jsonModel[] = $colRec;
            }
            $colRec = array();
            $colRec["columnName"] = $property;
            $colRec["width"] = 250;
            $colRec["label"] = $columnLabel;
            $colRec["align"] = "left";
            $colRec["isProperty"] = true;

            $jsonModel[] = $colRec;

            $colModel = json_encode($jsonModel);
            $returnColModel = true;
        } else
            $jsonModel = json_decode($colModel, true);
        
        $page = $request->request->get("page");
        $recPerPage = $request->request->get("nbRec");
        $nbPages = ceil($totalRec / $recPerPage);

        $jsonObj = null;
        $jsonArr = array();

        if ($list != null && sizeof($list) > 0) {
            $colName = null;
            $colVal = null;

            foreach ($list as $bean) {
                if ($bean == null)
                    continue;

                $jsonObj = array();

                foreach ($jsonModel as $modelRec) {
                    $colName = $modelRec["columnName"];
                    $colVal = $bean[$colName];

                    $jsonObj[$colName] = $colVal;
                }

                $jsonArr[] = $jsonObj;
            }
        }

        $jsonObj = array();

        $jsonObj["page"] = $page;
        $jsonObj["total"] = $nbPages;
        $jsonObj["records"] = $totalRec;
        if ($returnColModel)
            $jsonObj["colModel"] = $colModel;
        $jsonObj["rows"] = $jsonArr;

        return new JsonResponse($jsonObj);
    }
}
