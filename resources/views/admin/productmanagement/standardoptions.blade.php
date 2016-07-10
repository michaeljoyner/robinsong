@extends('admin.base')

@section('head')
    <meta id="x-token" property="CSRF-token" content="{{ Session::token() }}"/>
@stop

@section('content')
    <div class="rs-page-header">
        <h1 class="pull-left">Standard Options</h1>

        <div class="rs-header-actions pull-right">
            <form class="input-and-go-form" action="" v-on:submit.stop.prevent="makeOption">
                <input type="text" placeholder="Option name" v-model="newName">
                <button v-on:click.stop.prevent="makeOption" type="button" class="btn rs-btn btn-clear">
                    Create Option
                </button>
            </form>
        </div>
        <hr>
    </div>
    <section class="standard-options">
        <p class="lead">This is where you can manage you shared or common product options. Note that editing options here does not affect products where you have already set the options.</p>
        <standard-option v-for="option in standardOptions"
                         :option-id="option.id"
                         :option-name="option.name"
        ></standard-option>
    </section>
@endsection

@section('bodyscripts')
    <script>
        Vue.config.debug = true;
        new Vue(app.vueConstructorObjects.standardOptionsVue);
    </script>
@endsection