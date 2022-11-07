var frmValidator;
var menuListArray = [];
$(document).ready(function () {
    frmValidator = new TTFormValidator("#formId", {msgPosition: "bottom"});

    frmValidator.addCustomRule("#name", "checkNameDuplicate", Translator.trans("Account type name already exist."), function(value)
    {
        var typeName = $("#typeName").val();
        var inputTypeName = $("input[name='name']").val().trim();

        if (typeName != inputTypeName) {
            return result = checkDuplicate(inputTypeName, '');
        } else {
            return true;
        }
    });

    frmValidator.addCustomRule("#slug", "checkSlugDuplicate", Translator.trans("Slug already exists."), function(value)
    {
        var typeSlug = $("#typeSlug").val();
        var inputSlug = $("input[name='slug']").val().trim();

        if (typeSlug != inputSlug) {
            return result = checkDuplicate('', inputSlug);
        } else {
            return true;
        }
    });
});

function checkDuplicate(typeName, inputSlug) {
    var result = false;
    $.ajax({
	url: '/corporate/admin/accountType/check-duplicate',
	data: { accountTypeName: typeName, slug: inputSlug },
	type: 'POST',
	async: false,
    dataType: "json",
	success: function (response) {
	    result = response.success;
        return result;
	},
	error: function (error) {
	    console.log(error);
	}
    });
    return result;
}