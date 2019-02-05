var url = "http://{server}/{workspace}}/oauth2/token";
var method = "POST";
var clientID = "Your Client ID";
var clientSecret = "Your Client Secret";
var user = "Your username";
var password = "Your username";

$.ajax({
    url: url,
    type: method,
    data: JSON.stringify({
        "grant_type": "password",
                "scope": "",
                "client_id": clientID,
                "client_secret":clientSecret,
                "username": user,
                "password": password
    }),
    async: false, //with false => sync ; true => async
    contentType: "application/json",
    success: function (data, textStatus) {
       console.log(data);
    },
    error: function (xhr, textStatus, errorThrown) {
        console.log(textStatus);
    }
});