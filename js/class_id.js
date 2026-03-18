function class_id(){
    var subject =document.getElementById("sub").value;
    var x = Math.floor(1000 + Math.random() * 9000);

    if(subject=="Biology"){
        var sub="Bio";
    }
    else if(subject=="Applied Maths"){
        var sub ="AMath";
    }
    else if(subject=="pure Maths"){
        var sub ="PMath";
    }
    else if(subject=="Physic"){
        var sub ="Phy";
    }
    else if(subject=="Chemistry"){
        var sub ="Chem";
    }
    else if(subject=="Accounting"){
        var sub ="Acc";
    }
    else if(subject=="Business Studies"){
        var sub ="BS";
    }
    else if(subject=="Economics"){
        var sub ="EC";
    }
    else if(subject=="Engineering Technology"){
        var sub ="ET";
    }
    else if(subject=="Bio System Technology"){
        var sub ="BST";
    }
    else if(subject=="Science For Technology"){
        var sub ="SFT";
    }
    else if(subject=="Information communication Technology"){
        var sub ="ICT";
    }
    
    else{
        alert("Select subject");
    }


    document.getElementById("clzId").value = sub+"-"+x;
    

}