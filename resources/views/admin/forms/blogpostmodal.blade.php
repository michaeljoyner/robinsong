<div class="modal fade rs-form-modal" id="blogpost-form-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Create a new blog post</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => '/admin/blog/posts', 'class' => 'rs-form form-horizontal']) !!}
                <p>The title you enter now will be used to create the url for the post. However, the url can not be updated once created. It is fine if you make minor changes to the title at a later stage, but if you do something completely stupid, you'll have to delete the entire post.</p>
                @include('errors')
                <div class="form-group">
                    <label for="name">Title: </label>
                    {!! Form::text('title', null, ['class' => "form-control"]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn rs-btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn rs-btn">Create Post</button>
                {!! Form::close() !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->