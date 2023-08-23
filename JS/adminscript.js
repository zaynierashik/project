    // Toggle Table

    var currentManageTable = document.getElementById("feedback-count-table");

    function showcounttable(tableId){
        var manageTable = document.getElementById(tableId);
        if(currentManageTable !== null && currentManageTable !== manageTable){
            currentManageTable.style.display = "none";
        }

        if(manageTable.style.display === "none"){
            manageTable.style.display = "block";
            currentManageTable = manageTable;
        }
    }

    function hideAllManageTables(){
        var manageTables = document.getElementsByClassName("user-count-table");
        for(var i = 0; i < manageTables.length; i++){
            manageTables[i].style.display = "none";
        }
        currentManageTable = null;
    }
    
    currentManageTable.style.display = "block";

    // Show Password

    function showPassword(){
        var x = document.getElementById("password");
        if(x.type == "password"){
            x.type = "text";
        }else{
            x.type = "password";
        }
    }

    function showAdminPassword(){
        var x = document.getElementById("adminpassword");
        if(x.type == "password"){
            x.type = "text";
        }else{
            x.type = "password";
        }
    }