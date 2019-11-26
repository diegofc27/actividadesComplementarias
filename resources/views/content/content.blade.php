    @extends('main')
    @section('content')

    

        <template @if(Auth::check() && Auth::user()->id_role == "1") v-if="menu==0 || menu==1" @else v-if="menu==1" @endif>
            <!-- Grupos por extraescolares -->
            <group></group>
        </template>

        <template v-if="menu==2">
            <!-- Agregar grupo extraescolar -->
            <item></item>
        </template>

        <template v-if="menu==3">
            <!-- Grupos por Q8 -->
            <q8></q8>
        </template>

        <template v-if="menu==4">
            <!-- Subir Calificaciones -->
            <provider></provider>
        </template>

        <template v-if="menu==5">
            <!-- Gestion de Maestros -->
            <teacher></teacher>
        </template>

        <template v-if="menu==6">
            <!-- Gestión Alumnos -->
            <student></student>
        </template>

        <template v-if="menu==7">
        </template>

        <template v-if="menu==8">
            <!-- Historial de Inscripciones -->
        </template>

        <template v-if="menu==9">
            <!-- Configuracion de Grupos -->
        </template>

        <template v-if="menu==10">
            <!-- Configuracion de Alumnos -->
        </template>

        <template v-if="menu==11">
            <!-- Configuracion de Maestros -->
        </template>

        <template v-if="menu==12">
            <!-- Usuarios -->
            <user></user>
        </template>

        <template v-if="menu==13">
            <!-- Agregar Usuario -->
            <create_user></create_user>
        </template>

        <template v-if="menu==14">
            <!-- Roles de Usuario -->
            <role></role>
        </template>

        <template v-if="menu==15">
            <!-- Generar reporte de grupo -->
        </template>

        <template v-if="menu==16">
            <!-- Reporte de alumno -->
        </template>

        <template v-if="menu==17">
            <!-- Historial de reportes -->
        </template>

        <template v-if="menu==18">
            <!-- Acerca de -->
        </template>


        @if(Auth::check() && Auth::user()->id_role == "2")
            <template @if(Auth::check() && Auth::user()->id_role == "2") v-if="menu==0 || menu==22" @else v-if="menu==22" @endif>
                <!-- Grupos de Maestro -->
                <grupos_maestro></grupos_maestro>
            </template>

            <template v-if="menu==20">
                <!-- Grupos de Maestro -->
                <califications></califications>
            
            </template>
            <?php $var = "eaeaeaeAEA"  ?>

            <template v-if="menu==21">
                <!-- Grupos de Maestro -->
                <students_list></students_list>
            </template>
        @endif
        <template v-if="menu==19">
            <inscripcion></inscripcion>
            <!-- Inscripcion Alumnos -->
        </template>

    @endsection
        