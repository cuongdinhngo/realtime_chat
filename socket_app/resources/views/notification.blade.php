@php
use Carbon\Carbon;
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="list-group list-group-flush">
                @foreach ($userNotifications as $notify)
                    @php
                        $readAt = $notify->read_at ? 'list-group-item-light' : '';
                    @endphp
                    <a href="{{route('rooms.enter', ['room_id' => $notify->data['room_id']])}}" class="list-group-item {{$readAt}}">{{getNotifyMessage($notify->data)}} ({{Carbon::parse($notify->created_at)->diffForHumans(now())}})
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
