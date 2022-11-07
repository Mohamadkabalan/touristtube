var frmValidator;
$(document).ready(function () {
    //
    var ctryOpts = {
    		url: generateLangURL("/corporate/SearchCountry", "ajax"),
    		searchButton: false,
    		resetButton: false,
    		colModel: [	{ 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
    			{'dbName' : 'p.code' ,'columnName':'code','width':'80px','label':'code'},
    			{'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
		    ]
        };
    var comboGridCountry = new AutoCompleteComboGrid("country", ctryOpts);
    //
    frmValidator = new TTFormValidator("#formId", {msgPosition: "bottom"});
});