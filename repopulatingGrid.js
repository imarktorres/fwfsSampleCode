function clearGrid(gridName) {
   var grd = getObject(gridName);
   var i = Number_Grid_Rows(gridName, grd.aFields[0].sFieldName);
   for (i; i > 1; i--) {
       grd.deleteGridRow(i, true);
   }
   //The first row can't be deleted, so clear the fields in the first row:
   for (i = 0; i < grd.aFields.length; i++) {
       getGridField(gridName, 1, grd.aFields[i].sFieldName).value = "";
   }
}
function doQuery() {
   var q = this.value;
   if (q == "") {
      clearGrid();
      return;
   }
   if (window.XMLHttpRequest) // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
   else // code for IE5, IE6
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
 
   xmlhttp.onreadystatechange=function() {
      if (xmlhttp.readyState==4 && xmlhttp.status==200) {
         var rows = eval( '(' + xmlhttp.responseText + ')' );
         var grd = getObject("clientsGrid");
         clearGrid("clientsGrid");
         for (var i = 1; i < rows.length; i++) {
            if (Number_Grid_Rows("clientsGrid", "firstName") != 1) {
               grd.addGridRow();
            }
            getGridField("clientsGrid", i, "firstName").value = rows[i].firstName;
            getGridField("clientsGrid", i, "lastName").value = rows[i].lastName;
            getGridField("clientsGrid", i, "telephone").value = rows[i].telephone;
            getGridField("clientsGrid", i, "address").value = rows[i].address;
         }
      }
   }
   xmlhttp.open("GET", "http://my-address/query.php?q="+str, true);
   xmlhttp.send();
}
getField("MyTextbox").onchange=doQuery;