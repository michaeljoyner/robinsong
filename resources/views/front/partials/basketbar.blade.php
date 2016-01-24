<div class="w-section w-clearfix basket-section">
    <div id="basket" class="p1 basket">
        <a href="/cart">Basket: <span>@{{ number_items }}</span> Items</a>
        <div class="basket-popout" v-bind:class="{'show': open}">
            <div class="basket-counts">
                <p class="basket-stat">@{{ product_count }} Products</p>
                <p class="basket-stat">@{{ number_items }} Items</p>
            </div>
            <p class="basket-price">&pound;@{{ total_price / 100 }}</p>
        </div>
    </div>
</div>