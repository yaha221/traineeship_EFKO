$.get("http://app-calculator-yii2/home/test", function(data){ 
    console.log(data); 
    $("#result-box").html(data);});