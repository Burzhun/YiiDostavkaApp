function PartnerClick(id,name,category) {
    ga('ec:addProduct', {
        'id': id,
        'name': name,
        'category': category,
    });
    ga('ec:setAction', 'detail');
    ga('send', 'pageview');
}
function addToCart(id,name,category,price,quantity){
    ga('ec:addProduct', {
        'id': id,
        'name': name,
        'category': category,
        'price': price,
        'quantity': quantity
    });
    ga('ec:setAction', 'add');

    ga('send', 'event', 'UX', 'click', 'add to cart');
}
function removeFromCart(id,name,category,price,quantity){
    ga('ec:addProduct', {
        'id': id,
        'name': name,
        'category': category,
        'price': price,
        'quantity': quantity
    });
    ga('ec:setAction', 'remove');

    ga('send', 'event', 'UX', 'click', 'remove from cart');
}
function addCart(orders){
    //console.log(orders);
    for(var i=0;i<orders.length;i++){
        var order=orders[i];
        ga('ec:addProduct', {
            'id': order.id,
            'name': order.name,
            'category':order.category,
            'price': order.price,
            'quantity': order.quality
        });
    }
    ga('ec:setAction','checkout', {
        'step': 1             // Used to specify additional info about a checkout stage, e.g. payment method.
    });

    ga('send', 'pageview');
}
function addOrder(orders,id,price,delivery){
    //console.log(orders);
    for(var i=0;i<orders.length;i++){
        var order=orders[i];
        ga('ec:addProduct', {
            'id': order[0],
            'name': order[1],
            'category': order[2],
            'price': order[3],
            'quantity': order[4]
        });
    }
    ga('ec:setAction', 'purchase', {          // Transaction details are provided in an actionFieldObject.
        'id': id,                         // (Required) Transaction id (string).
        'affiliation': 'Dostavka05', // Affiliation (string).
        'revenue': price,                        // Tax (currency).
        'shipping': delivery,           // Transaction coupon (string).
    });
    ga('send', 'pageview');
}
$(window).load(function(){    
    //pc
    $("#button1 .button_order").click(function(){
        var name=$(this).attr('param_name');
        var id=$(this).attr('param_id');
        var category=$(this).attr('category');
        var price=$(this).attr('price');
        var quantity=1;
        //console.log(ga);
        addToCart(id,name,category,price,quantity);
    });
    //pc
    $(".more-cola .button_order").click(function(){
        var name=$(this).attr('param_name');
        var id=$(this).attr('param_id');
        var category="Дополнительные напитки";
        var price=$(this).attr('price');
        var quantity=1;
        addToCart(id,name,category,price,quantity);
    });
    //mobile
    $(".orderLinkBox .orderLink").click(function(){
        var name=$(this).attr('param_name');
        var id=$(this).attr('data-goodid');
        var category=$(this).attr('category');
        var price=$(this).attr('price');
        var quantity=1;
        addToCart(id,name,category,price,quantity);
    });
    //mobile
    $(".more-cola .button_order").click(function(){
        var name=$(this).attr('param_name');
        var id=$(this).attr('param_id');
        var category="Дополнительные напитки";
        var price=$(this).attr('price');
        var quantity=1;
        addToCart(id,name,category,price,quantity);
    });
    //pc
    $("body").on('click','#popup .bascet-list .close-list',function(){
        var id=$(this).attr('param_id');
        var name=$(this).attr('param_name');
        var category=$(this).attr('param_category');
        var price=$(this).attr('param_price');
        var quantity=$(this).attr('param_num');
        removeFromCart(id,name,category,price,quantity);
    });
    //mobile
    $("body").on('click','.orderListRight .count-block .minusProduct',function(){
        var id=$(this).attr('param_id');
        var name=$(this).attr('param_name');
        var category=$(this).attr('param_category');
        var price=$(this).attr('param_price');
        var quantity=1;
        removeFromCart(id,name,category,price,quantity);
    });
    $("body").on('click','.orderListRight .count-block .plusProduct',function(){
        var id=$(this).attr('param_id');
        var name=$(this).attr('param_name');
        var category=$(this).attr('param_category');
        var price=$(this).attr('param_price');
        var quantity=1;
        addToCart(id,name,category,price,quantity);
    });
});
