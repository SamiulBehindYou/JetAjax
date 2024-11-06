@extends('task.layout')

@section('main')

<div class="row mt-4">
    <div class="col-md-10 m-auto">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="text-white text-center">Add Task</h3>
            </div>
            <div class="card-body">
                {{-- <strong class="text-success" id="success"></strong> --}}
                <form id="TaskForm">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <label for="" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Title deadline</label>
                            <input type="text" name="deadline" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="" class="form-label">Task PDF</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Description</label>
                            <textarea name="description" rows="5" id="desp" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-primary" id="TaskBtn">Add Task</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


{{-- row --}}
<div class="row mt-4">
    <div class="col-md-10 m-auto">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="text-white text-center">Running Task</h3>
            </div>
            <div class="card-body">
                <table class="table text-center" id="taskTable">
                        <tr>
                            <th>SL</th>
                            <th>Title</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                </table>
            </div>
        </div>
    </div>
</div>

{{-- row --}}
<div class="row mt-4">
    <div class="col-md-10 m-auto">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="text-white text-center">Completed Task</h3>
            </div>
            <div class="card-body">
                <table class="table text-center" id="completedTask">

                        <tr>
                            <th>SL</th>
                            <th>Title</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                </table>
            </div>
        </div>
    </div>
</div>

{{-- row --}}
<div class="row mt-4">
    <div class="col-md-10 m-auto">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="text-white text-center">Accepted Task</h3>
            </div>
            <div class="card-body">
                <table class="table text-center" id="acceptedTask">

                        <tr>
                            <th>SL</th>
                            <th>Title</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')

<script>
    // add data
    $(document).ready(function(){
        $("#TaskForm").submit(function(event){
            event.preventDefault();

            let form = $("#TaskForm")[0];
            let data = new FormData(form);

            $("#btnSubmit").prop("disabled", true);

            $.ajax({
                type:"POST",
                url:"{{ route('task.store') }}",
                data:data,
                processData:false,
                contentType:false,
                success:function(data){
                    // $("#success").text(data.res);
                    alert(data.res);
                    $("#btnSubmit").prop("disabled", false);
                    $("input[type='text']").val('');
                    $("input[type='file']").val('');
                    $("#desp").val('');
                },
                error:function(e){
                    console.log(e.responseText);
                    $("#btnSubmit").prop("disabled", false);
                },
            });
        });


        // Get data
        $.ajax({
            type:"GET",
            url:"{{ route('gettask') }}",
            success:function(data){

                if(data.tasks.length > 0){
                    for(let i=0; i<data.tasks.length; i++){
                        if(data.tasks[i]['task_status'] == 0){
                            let assignee = (data.tasks[i]['task_asginee']);
                        $("#taskTable").append(
                            `<tr>
                                <td>`+(i+1)+`</td>
                                <td>`+(data.tasks[i]['task_title'])+`</td>
                                <td>`+(data.tasks[i]['task_deadline'])+`</td>
                                <td>Running</td>
                                <td>
                                    <a href="" class="btn btn-success makeDone" data-id="`+data.tasks[i]['id']+`">Make Done!</a>
                                    <a href="{{ asset('storage/pdf') }}/`+(data.tasks[i]['task_pdf'])+`" download class="btn btn-info">PDF</a>
                                    <a href="#" class="btn btn-danger deleteData" data-id="`+data.tasks[i]['id']+`">Delete</a>
                                </td>
                            </tr>`
                        );
                        }
                    }

                    for(let i=0; i<data.tasks.length; i++){
                        if(data.tasks[i]['task_status'] == 1){
                            let assignee = (data.tasks[i]['task_asginee']);
                        $("#completedTask").append(
                            `<tr>
                                <td>`+(i+1)+`</td>
                                <td>`+(data.tasks[i]['task_title'])+`</td>
                                <td>`+(data.tasks[i]['task_deadline'])+`</td>
                                <td>Compeleted</td>
                                <td>
                                    <a href="" class="btn btn-danger reject" data-id="`+data.tasks[i]['id']+`">Reject</a>
                                    <a href="" class="btn btn-success accept" data-id="`+data.tasks[i]['id']+`">Accept</a>
                                </td>
                            </tr>`
                        );
                        }
                    }

                    for(let i=0; i<data.tasks.length; i++){
                        if(data.tasks[i]['task_status'] == 2){
                            let assignee = (data.tasks[i]['task_asginee']);
                        $("#acceptedTask").append(
                            `<tr>
                                <td>`+(i+1)+`</td>
                                <td>`+(data.tasks[i]['task_title'])+`</td>
                                <td>`+(data.tasks[i]['task_deadline'])+`</td>
                                <td>Accepted</td>
                                <td>
                                    <a href="#" class="btn btn-danger deleteData" data-id="`+data.tasks[i]['id']+`">Delete</a>
                                </td>
                            </tr>`
                        );
                        }
                    }
                }else{
                    $("#taskTable").append("<tr><td colspan='4'>No task found!</td></tr>");
                }
            },
            error:function(er){
                console.log(er.responseText);
            },
        });


        // Make Done!
        $("#taskTable").on("click", ".makeDone", function(){
            
            let dataId = $(this).attr("data-id");
            let objectOfData = $(this);
            $.ajax({
                type:"GET",
                url:"make-done/"+dataId,
                success:function(data){
                    objectOfData.parent().parent().remove();
                    alert(data.done);
                },
                error:function(err){
                    console.log(err.responseText)
                }
            });
        });

        // accept
        $("#completedTask").on("click", ".accept", function(){
            let dataId = $(this).attr("data-id");
            let objectOfData = $(this);
            $.ajax({
                type:"GET",
                url:"accept/"+dataId,
                success:function(data){
                    objectOfData.parent().parent().remove();
                    alert(data.accept);
                },
                error:function(err){
                    console.log(err.responseText)
                }
            });
        });

        // reject
        $("#completedTask").on("click", ".reject", function(){
            let dataId = $(this).attr("data-id");
            let objectOfData = $(this);
            $.ajax({
                type:"GET",
                url:"reject/"+dataId,
                success:function(data){
                    objectOfData.parent().parent().remove();
                    alert(data.reject);
                },
                error:function(err){
                    console.log(err.responseText)
                }
            });
        });

        //Delete data
        $("#taskTable").on("click", ".deleteData", function(){
            let dataId = $(this).attr("data-id");
            let objectOfData = $(this);
            $.ajax({
                type:"GET",
                url:"delete-task/"+dataId,
                success:function(data){
                    objectOfData.parent().parent().remove();
                    alert(data.success);
                },
                error:function(err){
                    console.log(err.responseText)
                }
            });
        });

        //Delete accepted data
        $("#acceptedTask").on("click", ".deleteData", function(){
            let dataId = $(this).attr("data-id");
            let objectOfData = $(this);
            $.ajax({
                type:"GET",
                url:"delete-task/"+dataId,
                success:function(data){
                    objectOfData.parent().parent().remove();
                    alert(data.success);
                },
                error:function(err){
                    console.log(err.responseText)
                }
            });
        });
    });

</script>

@endsection


{{-- <td>{{ App\Models\User::find(1)->name; }}</td> --}}
