
function calculatePrice(viewid, price) {
    var qty = document.getElementById("qty" + viewid).value;
    var txt = document.getElementById("price" + viewid);

    if (qty > 0) {
        num = price * qty;
    } else
        num = price;

    txt.innerHTML = (Math.round(num * 100) / 100).toFixed(2);

    calculateTotal();
}

function calculateTotal(){

    var total = document.getElementById("total");

    sum = 0;

    for (i = 0 ; true; i++){

        if(!document.getElementById("price"+i))
            break;
        
        sum += parseFloat(document.getElementById("price"+i).innerHTML);

    }

    total.innerHTML = (Math.round(sum * 100) / 100).toFixed(2);
}