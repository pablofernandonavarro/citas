<x-admin-layout title="Doctores" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Calendario',
        'href' => route('admin.calendar.index'),
    ],
]">

@push('css')
<style>
    .fc-event{
        cursor : pointer;
    }
</style>

@endpush


    <div x-data="data()">


 
<x-wireui-modal-card
 title="Turno Médico" 
 name="appointmentModal"
 align="center">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Fecha y Hora</label>
            <p class="mt-1 text-sm text-gray-900" x-text="selectedEvent.datetime"></p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Paciente</label>
            <p class="mt-1 text-sm text-gray-900" x-text="selectedEvent.patient"></p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Doctor</label>
            <p class="mt-1 text-sm text-gray-900" x-text="selectedEvent.doctor"></p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Estado</label>
            <p class="mt-1 text-sm" x-text="selectedEvent.status" :style="'color: ' + selectedEvent.color"></p>
        </div>
    </div>
 
    <x-slot name="footer" class="flex justify-between gap-x-4">
        <x-wireui-button 
            flat 
            positive 
            label="Gestionar consulta" 
            x-on:click="window.open(selectedEvent.url, '_blank')" 
        />
 
        <div class="flex gap-x-4">
            <x-wireui-button flat label="Cerrar" x-on:click="close" />
        </div>
    </x-slot>
</x-wireui-modal-card>


        <div x-ref="calendar">

        </div>





    </div>


    @push('js')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>

        <script>
            function data() {
                return {
                   selectedEvent: {
                    datetime: '',
                    patient: '',
                    doctor: '',
                    status: '',
                    color: '',
                    url: '', 
                   },

                    init() {
                        var calendarEl = this.$refs.calendar;
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                            },
                            locale:'es',
                            firstDay: 1,
                            buttonText :{
                                month: 'Mes',
                                week: 'semana',
                                day: 'Día',
                                list: 'Lista',
                                today: 'hoy',

                            },
                            allDayText: 'Todo el día',
                            noEventsText: 'No hay eventos por mostrar',
                            initialView: 'timeGridWeek',
                            slotDuration: '00:20:00',
                            slotMinTime: "{{ config('schedule.start_time') }}",
                            slotMaxTime: "{{ config('schedule.end_time') }}",

                           events: {
                               'url' : '{{ route('api.appointments.index') }}',
                               failure: function(){
                                alert('hubo error al cargar');
                               }
                           },
                           eventClick: (info) => {
                            this.selectedEvent = {
                                datetime: info.event.extendedProps.datetime,
                                patient: info.event.extendedProps.patient,
                                doctor: info.event.extendedProps.doctor,
                                status: info.event.extendedProps.status,
                                color: info.event.backgroundColor,
                                url: info.event.extendedProps.url
                            };
                            $openModal('appointmentModal');
                           },


                            scrollTime: "{{ date('H:i:s') }}"
                        });
                        calendar.render();
                    },
                }
            }
        </script>
    @endpush



</x-admin-layout>
