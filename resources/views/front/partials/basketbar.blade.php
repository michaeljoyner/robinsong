<div class="w-section w-clearfix basket-section">
    <a href="#" class="social-nav-link">
        <img src="/images/assets/icon_facebook_w.png" alt="facebook link">
    </a>
    <a href="#" class="social-nav-link">
        <img src="/images/assets/icon_instagram_w.png" alt="instagram link">
    </a>
    <a href="#" class="social-nav-link">
        <img src="/images/assets/icon_pinterest_w.png" alt="pinterest link">
    </a>
    <a href="#" class="social-nav-link">
        <img src="/images/assets/icon_twitter_w.png" alt="twitter link">
    </a>
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