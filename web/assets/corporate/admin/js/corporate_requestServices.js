function searchForAvailibility(){
    var accountName = $("input[name=accountName]").val(); 
    var accountId = $("input[name=accountId]").val(); 
    var fromDate = $("input[name=fromDate]").val(); 
    var toDate = $("input[name=toDate]").val(); 
    var destinationCityId = $("input[name=destinationCityId]").val(); 
    var departureCityId = $("input[name=departureCityId]").val(); 
   
   window.location.href = generateLangURL("xx?accountName="+accountName+"&accountId="+accountId+"&fromDate="+fromDate+"&toDate"+toDate+"&destinationCityId"+destinationCityId+"&departureCityId"+departureCityId,'empty');
}