@extends('admin.layout')

@section('styles')

@endsection

@section('content')
    <div class="hitokoto-container mdl-grid" style="overflow-x: auto;">
        <div class="mdl-cell mdl-cell--2-col mdl-cell--hide-tablet mdl-cell--hide-phone"></div>
            <div style="word-break:break-all;max-width:1250px;width:100%;font-family: 'Helvetica Neue', Helvetica, 'PingFang SC', 'Hiragino Sans GB', 'Microsoft YaHei', '微软雅黑', Arial, sans-serif;margin-left:auto;margin-right:auto;">
                <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="width: 100%;">
                    <thead>
                    <tr style="white-space: nowrap">
                        <th class="mdl-data-table__cell--non-numeric">所有工单</th>
                        <th>
                            <span>工单标题</span>
                        </th>
                        <th>
                            <span>工单内容</span>
                        </th>
                        <th>
                            <span>提交时间</span>
                        </th>
                        <th>
                            <span>更新时间</span>
                        </th>
                        <th>
                            <span>工单状态</span>
                        </th>
                        <th>
                            <span>操作</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($tickets->isEmpty())
                        <tr>
                            <td></td><td></td><td></td><td>暂无工单</td><td></td><td></td><td></td>
                        </tr>
                    @else
                        @foreach($tickets as $key => $ticket)
                            <tr>
                                <td class="mdl-data-table__cell--non-numeric">#{{ $ticket->id }}</td>
                                <td>{{ $ticket->title }}</td>
                                <td>{{ substr($ticket->content,0, 30) }}...</td>
                                <td>{{ $ticket->open_time }}</td>
                                <td>{{ $ticket->last_update }}</td>
                                <td>@if($ticket->status == 1)等待处理@elseif($ticket->status == 3)已回复@else已关闭@endif</td>
                                <td>
                                    <a href="{{ url('tickets/show') }}/{{ $ticket->id }}">查看工单</a>@if($ticket->status == 1 || $ticket->status == 3)&nbsp;<a href="#" data-ticket="{{ $ticket->id }}" class="close_ticket">关闭工单</a>@endif&nbsp;<a href="#" data-ticket="{{ $ticket->id }}" class="delete_ticket">删除工单</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('.close_ticket').click(function(){
                if(confirm('是否关闭本条工单')) {
                    var ticketId = $(this).attr('data-ticket');
                    fetch("{{ url('tickets/close/') }}/" + ticketId, {
                      method: "POST",
                      headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      credentials: 'same-origin'
                    })
                      .then(function(res) {
                        return res.json();
                      })
                      .then(function(obj) {
                        if(obj.status == 'y') {
                            setTimeout(function () {
                                window.location.reload()
                            },500);
                        }
                      });
                }
            });
            $('.delete_ticket').click(function(){
                if(confirm('是否删除本条工单')) {
                    var ticketId = $(this).attr('data-ticket');
                    fetch("{{ url('tickets/delete/') }}/" + ticketId, {
                      method: "POST",
                      headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      credentials: 'same-origin'
                    })
                      .then(function(res) {
                        return res.json();
                      })
                      .then(function(obj) {
                        if(obj.status == 'y') {
                            setTimeout(function () {
                                window.location.reload()
                            },500);
                        }   
                      })
                }
            });
        });
    </script>
@endsection
