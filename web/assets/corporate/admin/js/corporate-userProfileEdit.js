var frmValidator;
var menuListArray = [];
$(document).ready(function () {
    frmValidator = new TTFormValidator("#formId", {msgPosition: "bottom"});

    frmValidator.addCustomRule("#name", "checkUserNameDuplicate", Translator.trans("User profile name already exists."), function(value)
    {
        var upName = $("#upName").val();
        var inputTypeName = $("input[name='name']").val().trim();

        if (upName != inputTypeName) {
            return result = checkDuplicate(inputTypeName, '');
        } else {
            return true;
        }
    });

    frmValidator.addCustomRule("#slug", "checkSlugDuplicate", Translator.trans("Slug already exists."), function(value)
    {
        var upSlug = $("#upSlug").val();
        var inputSlug = $("input[name='slug']").val().trim();

        if (upSlug != inputSlug) {
            return result = checkDuplicate('', inputSlug);
        } else {
            return true;
        }
    });
});

function checkDuplicate(inputName, inputSlug) {
    var result = false;
    $.ajax({
	url: '/corporate/admin/userProfiles/check-duplicate',
	data: { name: inputName, slug: inputSlug },
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