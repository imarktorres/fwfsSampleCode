var host = PMDynaform.getHostName();              // get the hostname
var ws = PMDynaform.getWorkspaceName();           // get the current workspace
var token = PMDynaform.getAccessToken();          // get the access Token

function getRoles() {
    $.ajax({
        url: host+"/api/1.0/"+ws+"/roles",        // endpoint URL
        // GET requests do not send parameters, but POST and PUT can set their data here:
        data: {},
        type: "GET",
        // Header with the access token:
        beforeSend: function(xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer '+token);
        },
        success: function(roles) {
            var list = "";                        // empty array created to store the return data
            for (i=0; i < roles.length; i++) {      // return an array of information about roles
                list += roles[i].rol_name;        // filter the .rol_name
                list += "\n";                     // concatenate with newline
            }
        $("#roles").setValue(list);               // set the information in the text area
        }
    });
}
$("#getRolesBtn").find('button').click(getRoles);