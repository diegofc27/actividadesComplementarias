<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criteria">
                                <option value="name">Nombre</option>
                                </select>
                                <input type="text" v-model="search" @keyup.enter="listStudent(1,search,criteria)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listStudent(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped" id="table_grupos">
                        <thead>
                            <tr>
                                <th>Lista de asistencia</th>
                                <th>Nombre del grupo</th>
                                <th>Numero actual de alumnos</th>
                                <th>Numero maximo de alumnos</th>
                                <th>Horario</th>
                            </tr>
                        </thead>
                        <tbody><!-- Aqui empiezan la lista de grupos ================================================================================= -->
                            <tr v-for="student in arrayStudent" :key="student.id">
                                <td>
                                    <button type="button" onclick="javascript:window.location.href='/admin/group_list'" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button>
                                </td>
                                <td v-text="student.group_name"></td>
                                <td v-text="student.num_students"></td>
                                <td v-text="student.max_students"></td>
                                <td v-text="student.schedule"></td>
                                
                            </tr>
                        </tbody>
                    </table>
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
        <div class="modal fade" tabindex="-1" :class="{'show': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                            <div class="form-group row">


                                <label v-if="actionType==1 || actionType==2" class="col-md-3 form-control-label" read for="text-input">Nombre</label>
                                <label v-else-if="actionType==3" class="col-md-3 form-control-label" for="text-input">Contraseña actual</label>
                                <div class="col-md-9">
                                    <input v-if="actionType==1 || actionType==2" type="text" v-model="name" class="form-control" readonly>
                                    <input v-else-if="actionType==3" type="password" v-model="currentPass" class="form-control" placeholder="Contraseña Actual">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label v-if="actionType==1 || actionType==2" class="col-md-3 form-control-label" for="text-input">Apellido Paterno</label><!-- Vistas -->
                                <label v-else-if="actionType==3" class="col-md-3 form-control-label" for="text-input">Contraseña Nueva</label>

                                <div class="col-md-9">
                                    <input v-if="actionType==1 || actionType==2" type="text" v-model="paternal_surname" class="form-control" readonly><!-- Datos desplegados -->
                                    <input v-else-if="actionType==3" type="password" v-model="newPass" class="form-control" placeholder="Contraseña Nueva">
                                </div>
                            </div>


                            <div class="form-group row" v-if="actionType==1">
                                <label class="col-md-3 form-control-label" for="text-input">Apellido Materno</label>
                                <div class="col-md-9">
                                    <input type="text-input" v-model="maternal_surname" class="form-control" placeholder="" readonly>
                                </div>
                            </div>

                            <div class="form-group row" v-if="actionType==1">
                                <label class="col-md-3 form-control-label" for="text-input">Grupo Asignado:</label>
                                <div class="col-md-9">
                                    <input type="text-input" v-model="group_name" class="form-control" placeholder="" readonly>
                                </div>
                            </div>

                            <div class="form-group row" v-if="actionType==1">
                                <label class="col-md-3 form-control-label" for="text-input">Calificación:</label><!-- Calificacion ============= -->
                                <div class="col-md-9">
                                    <input type="text-input" v-model="calification" class="form-control" placeholder="Calificacion">
                                </div>
                            </div>


                            
                            <div v-show="studentError" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in studentShowErrorMsg" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button type="button" v-if="actionType==1" class="btn btn-primary" @click="registerCalification()">Guardar</button>
                        <button type="button" v-if="actionType==2" class="btn btn-primary" @click="updateStudent()">Actualizar</button>
                        <button type="button" v-if="actionType==3" class="btn btn-primary" @click="pVal()">Cambiar Contraseña</button>
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
    $( document ).ready(function() {
        //var invisible = $('#invisible').value;
        var invisible = document.getElementById('invisible').innerHTML;
        console.log("El numero uno: "+invisible );
        $('#table_grupos').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
});

    export default {
       
        data(){
            return{

                //Global Variables
                name: '',
                username: '',
                password: '',
                currentPass: '',
                newPass: '',
                confirmPass:'',
                arrayStudent: [],
                calification: '',
                student_id: '',

                //Modal Variables
                modal: 0,
                modalTitle: '',
                actionType: 0,
                group: '',
                firstValue:[{"id":0},{"name":"AKjduiasjdoia"}],
                arrayGroups: [],

                //Validate Student
                studentError: 0,
                studentShowErrorMsg: [],

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
                criteria: invisible.innerHTML,
                search: '',
            }
        },
        computed:{

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
            exportGroups(){
                        $('#table_grupos').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            } );

            },
            listGroups(){
                let me = this;
                axios.get('/admin/getGroups/').then(function (response){
                    //Executed Succesfully
                    me.arrayGroups = response.data.groups;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            listStudent(page,search,criteria){
                console.log("El numero 2: "+invisible.innerHTML);
                let me = this;
                var url = '/admin/lista_grupos?page=' + page + '&search=' + search + '&criteria=' + criteria;
                
                //Categories table registries
                axios.post(url, {
                    'email_maestro' : criteria,
                }).then(function (response) {
                    var respuesta = response.data;
                    // handle success
                    me.arrayStudent = respuesta.students.data;
                    me.pagination = respuesta.pagination;
                    //console.log(me.arrayStudent);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            changePage(page, search, criteria){
                let me = this;

                //Update the current page
                me.pagination.current_page = page;

                //Send the current page view data request
                me.listStudent(page, search, criteria);
            },
            registerCalification(){

                if (this.validateStudentCalification()) {
                    return;
                }
                let me = this;
                axios.post('/admin/grupos_estudiantes/registrar', {
                    'id': this.student_id,
                    'grade': this.calification,
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                    Swal.fire({
                    type: 'success',
                    title: 'Éxito!',
                    text: 'La calificacion ha sido registrada correctamente!',
                    showConfirmButton: false,
                    timer: 2500
                    })
                    me.listStudent(1,'','name');
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
            registerStudent(){

                if (this.validateStudent()) {
                    return;
                }
                let me = this;
                axios.post('/admin/alumno/registrar', {
                    'username': this.username,
                    'password': this.password,
                    'name': this.name,
                    'id_group':this.group.id,
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                    Swal.fire({
                    type: 'success',
                    title: 'Éxito!',
                    text: 'Tu registro se ha creado correctamente!',
                    showConfirmButton: false,
                    timer: 2500
                    })
                    me.listStudent(1,'','name');
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
            updateStudent(){

                if (this.validateStudent()) {
                    return;
                }

                let me = this;
                var group = me.group;
                if (me.group != "") {
                    var group = me.group.id;
                }
                axios.put('/admin/alumno/actualizar', {
                    'name': this.name,
                    'username': this.username,
                    'id_group':group,
                    'id': this.student_id
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                    Swal.fire({
                        type: 'success',
                        title: 'Éxito!',
                        text: 'Tu registro se ha actualizado correctamente!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                    me.listStudent(1,'','name');
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
            changePassword(id_user){
                let me = this;
                axios.post('/admin/usuario/chgPs', {
                    'id_user': id_user,
                    'currentPassword': this.currentPass,
                    'newPass': this.newPass,
                    'confirmPass': this.confirmPass,
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                    Swal.fire({
                        type: 'success',
                        title: 'Éxito!',
                        text: 'Contraseña actualizada correctamente!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                    me.listStudent(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                    Swal.fire({
                    type: 'error',
                    title: 'Ups!',
                    text: 'Algo salió mal',
                    showConfirmButton: false,
                    timer: 3000
                    })
                });
            },
            pVal(){
                let me = this;
                axios.post('/admin/usuario/pVal', {
                    'currentPassword': this.currentPass,
                    'newPass': this.newPass,
                    'confirmPass': this.confirmPass,
                    'id_user': this.id_user
                }).then(function(response) {
                    //Executed Succesfully
                    if (response.data == '1') {
                        me.changePassword(me.id_user);
                    } else {
                        Swal.fire({
                        type: 'error',
                        title: 'Ups!',
                        text: response.data,
                        showConfirmButton: false,
                        timer: 2500
                        })
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validateStudent(){
                this.studentError=0;
                this.studentShowErrorMsg = [];
                if(!this.name) this.studentShowErrorMsg.push('El Nombre del Alumno no puede estar vacío.');
                if(!this.username) this.studentShowErrorMsg.push('El número de control no pueden estar vacíos.');
                //if(!this.password) this.studentShowErrorMsg.push('La contraseña no puede estar vacía.');
                if (this.studentShowErrorMsg.length) this.studentError = 1;
                
                return this.studentError;
            },
            validateStudentCalification(){
                this.studentError=0;
                this.studentShowErrorMsg = [];
                if(!this.calification) this.studentShowErrorMsg.push('La calificación del Alumno no puede estar vacía');
                if (this.studentShowErrorMsg.length) this.studentError = 1;
                
                return this.studentError;
            },
            closeModal(){
                this.modal = 0;
                this.modalTitle = '';
                this.name = '';
                this.username = '';
                this.password = '';
                this.studentError = 0;
            },
            openModal(model, action, data = []){
                switch (model) {
                    case "student":
                    {
                        switch (action) {
                            case 'calification':
                            {
                                console.log(data['id']);
                                //Show Modal
                                this.modal = 1;
                                this.modalTitle = 'Registrar Calificacion';
                                this.name = data['name'];
                                this.paternal_surname = data['paternal_surname'];
                                this.maternal_surname = data['maternal_surname'];
                                this.group_name = data['group_name'];
                                this.student_id = data['id'];
                                this.calification = '';
                                this.username = '';
                                this.password = '';
                                this.actionType = 1;
                                break;
                            }
                            case 'register':
                            {
                                //Show Modal
                                this.modal = 1;
                                this.modalTitle = 'Registrar Alumno';
                                this.name = '';
                                this.username = '';
                                this.password = '';
                                this.actionType = 1;
                                break;
                            }
                            case 'update':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Actualizar Alumno';
                                this.student_id = data['id'];
                                this.name = data['name'];
                                this.username = data['username'];
                                this.password = data['password'];
                                this.actionType = 2;
                                break;
                            }
                            case 'changePsw':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Cambiar Contraseña';
                                this.newPass = '';
                                this.currentPass = '';
                                this.confirmPass = '';
                                this.password = data['password'];
                                this.actionType = 3;
                                this.id_user = data['id_user']
                                break;
                            }
                        }
                    }
                }
            },
            deactivateStudent(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Desactivar Alumno',
                text: "¿Esta seguro que quiere desactivar este alumno?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.post('/admin/alumno/toggle', {
                        'id': id
                    }).then(function (response){
                        //Executed Succesfully
                        me.listStudent(1,'','name');

                        swalWithBootstrapButtons.fire(
                        'Eliminado Satisfactoriamente!',
                        'El registro ha sido desactivado satisfactoriamente.',
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

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },
        },
        mounted() {
            this.listStudent(1,this.search,this.criteria);
            this.listGroups();
            console.log('Component mounted.')
        },
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
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
