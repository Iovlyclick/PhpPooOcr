$firstP = $("p:first");
$firstP.click( function (){
    $firstP.after('<p id="secondP">Je te souhaite plein de bonheur !</p>');
    $secondP = $("#secondP"); 
    $secondP.click(function(){
        $secondP.after('<p id="thirdP"> Pour cette année et toutes les autres ! </p>')
        $thirdP = $('#thirdP'); 
        let test = false; 
        $thirdP.click(function(){
            if(!test){
                $('body').addClass('funky');
                $('body').removeClass('notFunky');

                test = true
            } else{
                $('body').addClass('notFunky');
                $('body').removeClass('funky');
                test = false
            }
            
        })
    })
})
