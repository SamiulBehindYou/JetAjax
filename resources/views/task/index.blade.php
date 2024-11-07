@extends('task.layout')

@section('main')
<div class="row mt-4">
    <div class="col-md-5 m-auto">
        <div class="text-center">
            <a class="btn btn-primary" target="blank" href="https://github.com/SamiulBehindYou/JetAjax">View Code on GitHub</a>

        </div>
        <div class="text-center">
            <p>If you have any queries, Please let me know!</p>
        </div>
        <div class="text-center mt-2">
            <h4>You are log in as  {{ Auth::user()->role == 1 ? 'Admin':'Employee' }}</h4>
            <a class="btn btn-primary" href="{{ route('dashboard') }}">Dashboard</a>
        </div>
    </div>
</div>

@if (Auth::user()->role == 1)
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
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Task assgin to:</label>
                        </div>
                        <div class="col">
                            <select name="assign" class="form-control">
                                <option value="">Select to assgin</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
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
@endif

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
                            <th>Assign to</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                </table>
            </div>
        </div>
    </div>
</div>

@if (Auth::user()->role == 1)

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
<div class="row mt-4 mb-4">
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
@endif
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
                    $(".IdOfTr").remove();
                    read();
                },
                error:function(e){
                    console.log(e.responseText);
                    $("#btnSubmit").prop("disabled", false);
                },
            });
        });
        read();

        function read(){
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
                                `<tr class="IdOfTr">
                                    <td>`+(i+1)+`</td>
                                    <td>`+(data.tasks[i]['task_title'])+`</td>
                                    <td>`+(data.tasks[i]['task_deadline'])+`</td>
                                    <td>`+(data.tasks[i]['task_assignee'])+`</td>
                                    <td>Incomplete</td>
                                    <td>
                                        <a href="" class="btn btn-success makeDone" data-id="`+data.tasks[i]['id']+`">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/></svg>
                                        </a>
                                        <a href="{{ asset('storage/pdf') }}/`+(data.tasks[i]['task_pdf'])+`" download class="btn btn-info">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-pdf" viewBox="0 0 16 16">
                                            <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1"/>
                                            <path d="M4.603 12.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.6 11.6 0 0 0-1.997.406 11.3 11.3 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.244.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 5.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/></svg>
                                        </a>
                                        <a href="#" class="btn btn-danger deleteData" data-id="`+data.tasks[i]['id']+`"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-1-square" viewBox="0 0 16 16">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/></svg>
                                        </a>
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
                                        <a href="" class="btn btn-danger reject" data-id="`+data.tasks[i]['id']+`">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-backspace" viewBox="0 0 16 16">
                                            <path d="M5.83 5.146a.5.5 0 0 0 0 .708L7.975 8l-2.147 2.146a.5.5 0 0 0 .707.708l2.147-2.147 2.146 2.147a.5.5 0 0 0 .707-.708L9.39 8l2.146-2.146a.5.5 0 0 0-.707-.708L8.683 7.293 6.536 5.146a.5.5 0 0 0-.707 0z"/>
                                            <path d="M13.683 1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-7.08a2 2 0 0 1-1.519-.698L.241 8.65a1 1 0 0 1 0-1.302L5.084 1.7A2 2 0 0 1 6.603 1zm-7.08 1a1 1 0 0 0-.76.35L1 8l4.844 5.65a1 1 0 0 0 .759.35h7.08a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/>
                                            </svg>
                                        </a>
                                        <a href="" class="btn btn-success accept" data-id="`+data.tasks[i]['id']+`">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-emoji-heart-eyes" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                            <path d="M11.315 10.014a.5.5 0 0 1 .548.736A4.5 4.5 0 0 1 7.965 13a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .548-.736h.005l.017.005.067.015.252.055c.215.046.515.108.857.169.693.124 1.522.242 2.152.242s1.46-.118 2.152-.242a27 27 0 0 0 1.109-.224l.067-.015.017-.004.005-.002zM4.756 4.566c.763-1.424 4.02-.12.952 3.434-4.496-1.596-2.35-4.298-.952-3.434m6.488 0c1.398-.864 3.544 1.838-.952 3.434-3.067-3.554.19-4.858.952-3.434"/>
                                            </svg>
                                        </a>
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
                                        <a href="#" class="btn btn-danger deleteData" data-id="`+data.tasks[i]['id']+`">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/></svg>
                                        </a>
                                    </td>
                                </tr>`
                            );
                            }
                        }
                    }else{
                        $("#taskTable").append("<tr><td colspan='6'>No task found!</td></tr>");
                        $("#completedTask").append("<tr><td colspan='5'>No task found!</td></tr>");
                        $("#acceptedTask").append("<tr><td colspan='5'>No task found!</td></tr>");
                    }
                },
                error:function(er){
                    console.log(er.responseText);
                },
            });
        }


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
