@extends('layouts.app')

@section('title') All SubPabbles @endsection

@section('stylesheets')
    <style>
        .searchbar{
            background: #ddd;
            display: flex;
            padding: 13px 20px;
        }
        .searchbar .fa-search.left{
            font-size: 26pt;
            margin: auto 0px;
        }
        .no-border{
            border: none;
        }
        .searchbar .description{
            font-size: 10pt;
        }
        .info-bar{
            background: #f6e69f;
            padding: 10px;
            border: 2px solid #fcb527;
        }
        .info-bar span{
            background: #fff;
        }
        .notsubscribed {
            background-color: #4CAF50 !important;
            color:white;
            border: 1px solid #4CAF50 !important;
            border-top: 1px solid #4CAF50 !important;
            margin: auto 0px;
        }
        .subscribed {
            background-color: #F44336 !important;
            color:white;
            border: 1px solid #F44336 !important;
            border-top: 1px solid #F44336 !important;
            margin: auto 0px;
        }
        button:hover, button:focus{
            color: #fff !important;
        }
        .title{
            font-size: 15pt;
        }
        .subpabble-item{
            display: flex;
        }
        .subpabble-item .description{
            border-radius: 5px;
            border: 1px solid #999;
            padding: 3px;
            background: #efefef;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row panel panel-default pb-3 no-border">
            <div class="searchbar">
                <span class="fa fa-search left"></span>
                <div class="col-sm-6 col-md-4 ml-6">
                    <span class="description">search subpabble by name</span>
                    <div class="input-group ">
                        <input value="{{ Request::input('q') }}" type="text" name="q" class="search-query form-control" placeholder="Search" />
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">
                                <span class="fa fa-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="info-bar">
                click the <span>subscribe</span> or <span>unsubscribe</span> buttons to choose which subpabble appear on the home feed.
            </div>
            @foreach($subpabbles as $item)
            <div class="col-md-12 mt-3 subpabble-item">
                <button class="btn btn-default notsubscribed">subscribe</button>
                <div class="ml-6">
                    <a href="" class="title">/p/name : name</a>
                    <div class="description">{{ $item->description }}</div>
                    a community for {{Carbon\Carbon::parse($item->created_at)->diffForHumans()}}
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
