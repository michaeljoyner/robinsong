<form id="rs-contact-form" class="rs-form" action="/contact" method="POST">
    {!! csrf_field() !!}
    <div class="rs-form-group">
        <label for="name">Your name: </label>
        <input type="text" name="name" required value="{{ old('name') }}"/>
    </div>
    <div class="rs-form-group">
        <label for="email">Email Address: </label>
        <input type="text" name="email" required value="{{ old('email') }}"/>
    </div>
    <div class="rs-form-group">
        <label for="enquiry">How can we help you?</label>
        <textarea name="enquiry" cols="30" rows="8"></textarea>
    </div>
    <div class="rs-form-group">
        <button class="cf-send-btn w-button purchase-btn blue full-width" type="submit">Send</button>
    </div>
    <div class="form-cover">
        <p id="cf-thanks">
            Thank you. We'll be in touch.
        </p>
        <p id="cf-reset">Send another message.</p>
    </div>
</form>