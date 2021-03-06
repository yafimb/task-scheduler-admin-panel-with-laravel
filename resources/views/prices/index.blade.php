@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>
                        Prices
                    </h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Shape</th>
                                <th>clarity</th>
                                <th>color</th>
                                <th>Low Size</th>
                                <th>High Size</th>
                                <th>Price</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--@foreach($prices as $price)--}}
                                {{--<tr class="table-{{$task->is_active ? 'success' : 'danger'}}">--}}
                                    {{--<td><a href="{{ route('tasks.edit', $task->id) }}">{{ $task->description }}</a></td>--}}
                                    {{--<td>{{ $task->last_run }}</td>--}}
                                    {{--<td>{{ $task->average_runtime }} seconds</td>--}}
                                    {{--<td>{{ $task->next_run }}</td>--}}
                                    {{--<td>--}}
                                        {{--<form id="toggle-form-{{$task->id}}" action="{{route('tasks.toggle', $task->id)}}" method="post">--}}
                                            {{--{{csrf_field()}}--}}
                                            {{--{{method_field('PUT')}}--}}

                                            {{--<input type="checkbox" {{$task->is_active ? 'checked' : ''}} onchange="getElementById('toggle-form-{{$task->id}}').submit();" data-offstyle="danger" data-toggle="toggle" data-size="small"/>--}}
                                        {{--</form>--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--<form id="delete-form-{{$task->id}}" action="{{route('tasks.destroy', $task->id)}}" method="post">--}}
                                            {{--{{csrf_field()}}--}}
                                            {{--{{method_field('DELETE')}}--}}
                                            {{--<button class="btn btn-sm btn-danger" type="button" onclick="if(confirm('Are You Sure?')) getElementById('delete-form-{{$task->id}}').submit();">Delete</button>--}}
                                        {{--</form>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
