@extends('layouts.app')

@section('content')
    <div class="container">
        <kanban-board :api-key="{{ json_encode(env('TASK_MANAGEMENT_API_KEY')) }}"></kanban-board>
    </div>
@endsection