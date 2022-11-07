var frmValidator;
var comboGridCity;
$(document).ready(function () {

    frmValidator = new TTFormValidator("#formId", {msgPosition: "bottom"});

    var ctryOpts = {
        url: generateLangURL("/corporate/SearchCountry", "ajax"),
        searchButton: false,
        resetButton: false,
        colModel: [ { 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': false, 'hidden': true},
            {'dbName' : 'p.code' ,'columnName':'code','width':'80px','label':'code','isIdProperty': true},
            {'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
            ]
    };
    var comboGridCountry = new AutoCompleteComboGrid("country", ctryOpts);

    var cityOpts = {
        parameter: "countryCode",
        url: generateLangURL('/corporate/SearchCity','ajax'),
        searchButton: false,
        resetButton: false,
        colModel: [ 
            { 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
            {'dbName' : 'p.fullname' ,'columnName':'fullname','width':'250px','label':'fullname','isProperty': true}
        ]
    };
    var comboGridCity = new AutoCompleteComboGrid("cityName", cityOpts);

    var currencyOpts = {
        url: generateLangURL('/corporate/SearchCurrency','ajax'),
        searchButton: false,
        resetButton: false,
        colModel: [ { 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
	            { 'dbName' : 'p.code' ,'columnName':'code','width':'80px','label':'code', 'isIdProperty': true, 'hidden': true},
	            {'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
            ]
    }; 
    var comboGridCurrency = new AutoCompleteComboGrid("currency", currencyOpts);
    var comboGridPreferredCurrency = new AutoCompleteComboGrid("preferredCurrency", currencyOpts);

    if($("#accountType").length){
        var accountTypeOpts = {
            url: generateLangURL('/corporate/account/searchAccountType','ajax'),
            searchButton: false,
            resetButton: false,
            colModel: [ 
                    { 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
                    {'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
                ]
        }; 
        var comboGridAccountType = new AutoCompleteComboGrid("accountType", accountTypeOpts);   
   }

    var parentOpts = {
    	parameter: "excludedAccountsIds",
        url: generateLangURL('/corporate/account/searchAccount','ajax'),
        searchButton: false,
        resetButton: false,
        colModel: [ 
        		{ 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
        		{'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
            ]
    }; 
    var comboGridParent = new AutoCompleteComboGrid("parent", parentOpts);

    if($("#agency").length){
        var agencyOpts = {
            url: generateLangURL('/corporate/admin/agencies/searchAgency','ajax'),
            searchButton: false,
            resetButton: false,
            colModel: [ 
                    { 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
                    {'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
                ]
        }; 
        var comboGridAgency = new AutoCompleteComboGrid("agency", agencyOpts);   
   }
    if($(".id").val() > 0){
        document.getElementById("formId").reset(); 
    }
});

function validateForm()
{
	return frmValidator.validate();
}

function onCountrySelected(selPropId, selPropVal, drpInpt)
{
    if($("#cityName").prop("disabled")){
        $("#cityName").removeAttr("disabled");
    }    

    window.countryCode = selPropId;
    //
    if(comboGridCity) comboGridCity.setValue({id: "", value: ""});
}
