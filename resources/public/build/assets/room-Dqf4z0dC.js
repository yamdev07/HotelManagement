$(function(){if(!window.location.pathname.split("/").includes("room"))return;const o=$("#room-table").DataTable({processing:!0,serverSide:!0,ajax:{url:"/room",type:"GET",data:function(t){t.status=$("#status").val(),t.type=$("#type").val()},error:function(t,e,n){}},columns:[{name:"number",data:"number"},{name:"type",data:"type"},{name:"capacity",data:"capacity"},{name:"price",data:"price",render:function(t){return`<div>${new Intl.NumberFormat().format(t)}</div>`}},{name:"status",data:"status"},{name:"id",data:"id",render:function(t){return`
                        <button class="btn btn-light btn-sm rounded shadow-sm border"
                            data-action="edit-room" data-room-id="${t}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit room">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form class="btn btn-sm delete-room" method="POST"
                            id="delete-room-form-${t}"
                            action="/room/${t}">
                            <a class="btn btn-light btn-sm rounded shadow-sm border delete"
                                href="#" room-id="${t}" room-role="room" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Delete room">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </form>
                        <a class="btn btn-light btn-sm rounded shadow-sm border"
                            href="/room/${t}"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Room detail">
                            <i class="fas fa-info-circle"></i>
                        </a>

                    `}}]}),a=new bootstrap.Modal($("#main-modal"),{backdrop:!0,keyboard:!0,focus:!0});$(document).on("click",".delete",function(){var t=$(this).attr("room-id");$(this).attr("room-name"),$(this).attr("room-url"),Swal.mixin({customClass:{confirmButton:"btn btn-success",cancelButton:"btn btn-danger"},buttonsStyling:!1}).fire({title:"Are you sure?",text:"Room will be deleted, You won't be able to revert this!",icon:"warning",showCancelButton:!0,confirmButtonText:"Yes, delete it!",cancelButtonText:"No, cancel! ",reverseButtons:!0}).then(n=>{n.isConfirmed&&$(`#delete-room-form-${t}`).submit()})}).on("click","#add-button",async function(){a.show(),$("#main-modal .modal-body").html("Fetching data");const t=await $.get("/room/create");t&&($("#main-modal .modal-title").text("Create new room"),$("#main-modal .modal-body").html(t.view),$(".select2").select2())}).on("click","#btn-modal-save",function(){$("#form-save-room").submit()}).on("submit","#form-save-room",async function(t){t.preventDefault(),CustomHelper.clearError(),$("#btn-modal-save").attr("disabled",!0);try{const e=await $.ajax({url:$(this).attr("action"),data:$(this).serialize(),method:$(this).attr("method"),headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});if(!e)return;Swal.fire({position:"top-end",icon:"success",title:e.message,showConfirmButton:!1,timer:1500}),a.hide(),o.ajax.reload()}catch(e){e.status===422&&(console.log(e),Swal.fire({icon:"error",title:"Oops...",text:e.responseJSON.message}),CustomHelper.errorHandlerForm(e))}finally{$("#btn-modal-save").attr("disabled",!1)}}).on("click",'[data-action="edit-room"]',async function(){a.show(),$("#main-modal .modal-body").html("Fetching data");const t=$(this).data("room-id"),e=await $.get(`/room/${t}/edit`);e&&($("#main-modal .modal-title").text("Edit room"),$("#main-modal .modal-body").html(e.view),$(".select2").select2())}).on("submit",".delete-room",async function(t){t.preventDefault();try{const e=await $.ajax({url:$(this).attr("action"),data:$(this).serialize(),method:$(this).attr("method"),headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});if(!e)return;Swal.fire({position:"top-end",icon:"success",title:e.message,showConfirmButton:!1,timer:1500}),o.ajax.reload()}catch{}}).on("change","#status",function(){o.ajax.reload()}).on("change","#type",function(){o.ajax.reload()})});
