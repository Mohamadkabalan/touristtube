var chk1 = $('#approvalForAllUsers');
var chk2 = $('#approvalUser');
var ttModal;

$(document).ready(function () {
    //check the other box
    chk1.on('click', function () {
	if (chk1.is(':checked')) {
	    chk2.attr('checked', true);
	}
    });
    ttModal = window.getTTModal("myModalZ", {});
    //
    $('#jstree_subA').jstree({
	"core": {
	    "animation": 0,
	    "check_callback": true,
	    "data": {"url": "/corporate/getApprovalUsersTree"}
	    , "themes": {"stripes": true}
	},
	"types": {
	    "#": {
		"valid_children": ["root"],
		"li_attr": {"class": "pinkarrowroot"}
	    },
	    "root": {
		"li_attr": {"class": "pinkarrowroot"},
		"valid_children": ["default"]
	    },
	    "default": {
		"valid_children": ["default", "file"],
		"li_attr": {"class": "pinkarrowroot"}
	    },
	    "file": {
		"li_attr": {"class": "pinkarrowroot"},
		"valid_children": []
	    }
	},
	"plugins": ["dnd", "search", "state", "types", "wholerow", "changed"]
    });
    $('#jstree_subA').on('ready.jstree,after_open.jstree', function (e, data) {
	addTreeButtons(null);
    });
    $('#jstree_subA').on('after_open.jstree', function (e, node) {
	addTreeButtons(node.node.id);
    });

    initFancyButtonsTree();
});

function addTreeButtons(nodeId)
{
    var html = '<div class="buttoncontent">'
	    + '<a href="javascript:void(0)" onclick="addFlow(this);" class="buttonstreestyle buttonsAddSub buttonsAddFancy">' + Translator.trans('add') + '</a>'
	    + '<a href="javascript:void(0)" onclick="editFlow(this);" class="buttonstreestyle buttonsAddSub buttonsAddFancy">' + Translator.trans('edit') + '</a>'
	    + '<a href="javascript:void(0)" onclick="deleteUser(this);" class="buttonstreestyle buttonsDeleteSub">' + Translator.trans('delete') + '</a>'
	    + '</div>';

    var selector = '#jstree_subA';
    if (nodeId)
	selector += ' #' + nodeId;
    //
    $(selector).find("a.jstree-anchor").append(html);

}

function initFancyButtonsTree() {
    $('.buttonsAddFancy').each(function () {
	var $this = $(this);
	$this.fancybox({
	    padding: 0,
	    margin: 0
	});
    });
}

function addFlow(btn)
{
    var userId = getNodeId(btn);
    window.location.href = generateLangURL('/corporate/definitions/approvalFlow/add/' + userId,'corporate');
}

function editFlow(btn)
{
    var userId = getNodeId(btn);
    window.location.href = generateLangURL('/corporate/definitions/approvalFlow/edit/' + userId,'corporate');
}
function deleteUser(btn)
{
    ttModal.confirmNO(Translator.trans("Are you sure you want to delete this user?"), function (action) {
        var userId = getNodeId(btn);
        var deleteChild;

        if (action == "ok") {
            deleteChild = false;
        } else if (action == "no") {
            deleteChild = true;
        }
        
        if(typeof deleteChild != 'undefined') {
            doDeleteUser(userId, deleteChild);
        }
    }, '',
    {
        ok: {
            value: Translator.trans('Yes'),
            type: 'danger'
        },
        no: {
            value: Translator.trans('Yes & Delete Sub Children'),
            type: 'danger'
        }
    });
}

function doDeleteUser(userId, deleteChild)
{
    $.ajax({
    url: '/corporate/definitions/approvalFlow/delete',
    data: {
        userId: userId,
        deleteChild: deleteChild
    },
    type: 'post',
    success: function (data) {
    //	    too reload the jstree not thepage
        window.location.href = generateLangURL('/corporate/definitions/approvalFlow','corporate');
    }
    });
}

function getNodeId(btnObj)
{
    return $(btnObj).parent().parent().parent().attr("id");
}

function getNodeText(btnObj)
{
    return $(btnObj).parent().parent().parent().text();
}
