
<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header ">
                    <div class="row justify-content-between">
                        <div class="mt-1">
                            <i class="fa fa-scribd"></i> Ciclo escolar - 
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <h1>Extraescolares Disponibles</h1>
                    <hr/>

                    <div v-for="activity in arrayActivities" :key="activity.id">
                        <h2 v-text="activity.name"></h2>
                        <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Grupo</th>
                                <th>Horario</th>
                                <th>Nombre del maestro</th>
                                <th>Cupo Limite</th>
                                <th>Cupo Restante</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="group in arrayGroup" :key="group.id" v-if="group.id_activity == activity.id">
                                <td v-text="group.name"></td>
                                <td v-text="group.schedule"></td>
                                <td v-text="group.teacher_name"></td>
                                <td v-text="group.max_students"></td>
                                <td v-text="group.max_students - group.num_students">
                                </td>
                                <td>
                                    <button type="button" @click="inscribirAlumno(activity, group)" class="btn btn-success">
                                        <i class="fa fa-sign-in"></i>
                                    </button> &nbsp;
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criteria">
                                <option value="name">Nombre</option>
                                <option value="description">Descripción</option>
                                </select>
                                <input type="text" v-model="search" @keyup.enter="listGroup(1,search,criteria)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listGroup(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1,search,criteria);">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="changePage(page,search,criteria)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page+1,search,criteria)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'show': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" >
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="modalTitle"></h4>
                        <button type="button" class="close" @click="closeModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> 
                            <div v-show="groupError" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in groupShowErrorMsg" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button type="button" v-if="actionType==1" class="btn btn-primary" @click="registerGroup()">Guardar</button>
                        <button type="button" v-if="actionType==2" class="btn btn-primary" @click="updateGroup()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>
<script>

    var horario = '';
    export default {
        data(){
            return{
                //Global Variables
                group_id: 0,
                name: '',
                id_teacher: '',
                id_activity:'',
                max_students: '',
                arrayGroup: [],
                arrayActivities: [],
                arrayTeachers: [],
                id_user: '',
                activity: '',
                teacher: '',
                actName: '',
                type: '',
                checkedNames: [],
                schedule: '',
                horario: '',

                //Modal Variables
                modal: 0,
                modalA: 0,
                modalTitle: '',
                actionType: 0,

                //Validate Group
                groupError: 0,
                groupShowErrorMsg: [],

                //Validate Activity
                activityError: 0,
                activityShowErrorMsg: [],

                //Pagination
                pagination: {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset: 3,
                criteria: 'name',
                search: '',
            }
        },
        computed:{
            groupActivities: function() {
                return this.arrayGroup.filter(function(u) {
                    return u.id_activity == activity.id;
                })
            },
            //Activated(?)
            isActived: function(){
                return this.pagination.current_page;
            },

            //Calculates the pagination elements
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            }
        },
        methods:{
            inscribirAlumno(actividad, grupo){
                console.log(actividad);
                console.log(grupo);
                Swal.fire({
                title: actividad.name + ': ' + grupo.name,
                html:"¿Estas seguro de inscribirte en esta extraescolar? </br> <b>Extraescolar: </b> " + actividad.name + '</br>' + '<b>Grupo: </b> ' + grupo.name + '</br>' + '<b> Horario: </b> ' + grupo.schedule + '</br>' + '<b> Maestro: </b> ' + grupo.teacher_name,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Inscribirme',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.value) {

                    let me = this;
                    axios.post('/alumno/inscribir', {
                        "id_group": grupo.id,
                        "id_user": this.id_user
                    }).then(function (response){
                        Swal.fire(
                        'Listo!',
                        'Ya estas inscrito!',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                        Swal.fire({
                        type: 'error',
                        title: 'Ups!',
                        text: 'Algo salió mal',
                        showConfirmButton: false,
                        timer: 2500
                        })
                    });

                    
                }
                })
            },
            listGroup(page,search,criteria){
                let me = this;
                var url = '/admin/grupo?page=' + page + '&search=' + search + '&criteria=' + criteria;
                
                //Categories table registries
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    // handle success
                    me.arrayGroup = respuesta.groups.data;
                    me.id_user = respuesta.id_user;
                    me.pagination = respuesta.pagination;
                    // console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listActivity(){
                let me = this;
                var url = '/actividad';
                
                //Categories table registries
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayActivities = respuesta.activities.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changePage(page, search, criteria){
                let me = this;

                //Update the current page
                me.pagination.current_page = page;

                //Send the current page view data request
                me.listGroup(page, search, criteria);
            },
            registerActivity(){
                if (this.validateActivity()) {
                    return;
                }
                let me = this;
                axios.post('/admin/actividad/registrar', {
                    'name': this.actName,
                    'type': this.type
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                    Swal.fire({
                        type: 'success',
                        title: 'Éxito!',
                        text: 'El grupo se ha creado correctamente!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                    me.listGroup(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                    Swal.fire({
                    type: 'error',
                    title: 'Ups!',
                    text: 'Algo salió mal',
                    showConfirmButton: false,
                    timer: 2500
                    })
                });
            },
            updateGroup(){
                if (this.validateGroup()) {
                    return;
                }
                let me = this;
                axios.post('/admin/grupo/actualizar', {
                    'id': this.id,
                    'name': this.name,
                    'id_teacher': this.id_teacher,
                    'max_students': this.max_students,
                    'id_activity': this.id_activity
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                    Swal.fire({
                        type: 'success',
                        title: 'Éxito!',
                        text: 'El grupo se ha actualizado correctamente!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                    me.listGroup(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                    Swal.fire({
                    type: 'error',
                    title: 'Ups!',
                    text: 'Algo salió mal',
                    showConfirmButton: false,
                    timer: 2500
                    })
                });
            },
            deactivateGroup(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Desactivar Grupo',
                text: "¿Esta seguro que quiere desactivar este grupo?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.put('/admin/grupo/toggle', {
                        'id': id
                    }).then(function (response){
                        //Executed Succesfully
                        me.listGroup(1,'','name');

                        swalWithBootstrapButtons.fire(
                        'Eliminado Satisfactoriamente!',
                        'El grupo ha sido desactivado satisfactoriamente.',
                        'success'
                        )
                    }).catch(function (error) {
                            console.log(error);
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },
            activateGroup(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Activar Grupo',
                text: "¿Esta seguro que quiere activar esta grupo?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.put('/admin/grupo/toggle', {
                        'id': id
                    }).then(function (response){
                        //Executed Succesfully
                        me.listGroup(1,'','name');

                        swalWithBootstrapButtons.fire(
                        'Activado!',
                        'El registro ha sido activado satisfactoriamente.',
                        'success'
                        )
                    }).catch(function (error) {
                            console.log(error);
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },
            validateGroup(){
                this.groupError=0;
                this.groupShowErrorMsg = [];

                if(!this.name) this.groupShowErrorMsg.push('El Nombre del grupo no puede estar vacío.');
                if (this.groupShowErrorMsg.length) this.groupError = 1;
                
                return this.groupError;
            },
            validateActivity(){
                this.activityError=0;
                this.activityShowErrorMsg = [];

                if(!this.actName) this.activityShowErrorMsg.push('El nombre de la actividad no puede estar vacío.');
                if (this.activityShowErrorMsg.length) this.activityError = 1;
                
                return this.activityError;
            },
            closeModal(){
                this.modal = 0;
                this.modalA = 0;
                this.modalTitle = '';
                this.name = '';
                this.id_teacher = '';
                this.max_students = 0;
                this.groupError = 0;
                this.activityError = 0;
            },
        },
        mounted() {
            this.listActivity();
            this.listGroup(1,this.search,this.criteria);
            // console.log('Component mounted.')
        }
    }

</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .show{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
