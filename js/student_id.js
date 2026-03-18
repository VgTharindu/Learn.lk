function student_id(){
    var nic =document.getElementById("nic").value;
    var year =document.getElementById("alYear").value;
    var stream =document.getElementById("alStream").value;

    var nic2 = nic.substring(8,12);

    if(stream=="Science"){
        var str="Bio";
    }
    else if(stream=="Science"){
        var str ="Math";
    }
    else if(stream=="Commerce"){
        var str ="Com";
    }
    else if(stream=="Arts"){
        var str ="Art";
    }
    else if(stream=="Technology"){
        var str ="Tech";
    }
    else{
        alert("Select stream");
    }

    document.getElementById("studentId").value= str+"\\"+year+"\\"+nic2;
    

}