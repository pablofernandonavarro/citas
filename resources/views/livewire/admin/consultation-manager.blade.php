<div>
    <x-wireui-card class="mb-6 bg-white">

        <div class="lg:flex lg:justify-between items-center">
            <div class="flex items-center space-x-5 mt-6 lg:mt-0">
                <img src="{{ $appointment->patient->user->profile_photo_url }}" alt="Profile Photo"
                    class="h-20 w-20 rounded-full object-cover object-center">

                <div>
                    <p class="text-2xl font-semibold text-gray-800">{{ $appointment->patient->user->name }}</p>
                    <p class="text-sm font-semibold text-gray-500">N° obra Social:
                        {{ $appointment->patient->medical_record_number ?? 'No disponible' }}</p>
                    <p class="text-sm font-semibold text-gray-500">DNI:
                        {{ $appointment->patient->user->dni ?? 'No disponible' }}</p>

                </div>
            </div>


            <div class="mt-6 lg:mt-0">

                <x-wireui-button outline gray sm x-on:click="$openModal('seeHistoryModal')">
                    <i class="fa-solid fa-notes-medical"></i>
                    Ver Historial
                </x-wireui-button>



                <x-wireui-button outline gray sm>
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Consultas Anteriores
                </x-wireui-button>
            </div>
        </div>
    </x-wireui-card>
    <x-wireui-card class="mb-6 bg-white">
        <x-tabs active="consultas">
            <x-slot name="header">
                <x-tab-link tab="consultas">
                    <i class="fa-solid fa-notes-medical me-2"></i>
                    Consultas
                </x-tab-link>
                <x-tab-link tab="recetas">
                    <i class="fa-solid fa-file-prescription me-2"></i>
                    Recetas
                </x-tab-link>
            </x-slot>

            <x-tab-content tab="consultas">
                <div class="space-y-4">
                    <x-wireui-textarea label="Diagnóstico" placeholder="Escribe el diagnóstico aquí..."
                        wire:model="form.diagnosis" rows="6" />

                </div>
                <div class="space-y-4">
                    <x-wireui-textarea label="Tratamiento" placeholder="Escribe el tratamiento aquí..."
                        wire:model="form.treatment" rows="6" />

                </div>
                <div class="space-y-4">
                    <x-wireui-textarea label="Notas" placeholder="Escribe las notas aquí..." wire:model="form.notes"
                        rows="6" />

                </div>
            </x-tab-content>

            <x-tab-content tab="recetas">
                <div class="space-y-4">
                    @forelse ($form['prescriptions'] as $index => $prescription)
                        <div class="bg-gray-50 p-4 rounded-lg flex gap-4" wire:key="prescription-{{ $index }}">
                            <div class="flex-1">
                                <x-wireui-input label="Medicamento" placeholder="Nombre del medicamento"
                                    wire:model="form.prescriptions.{{ $index }}.medicine" />
                            </div>

                            <div class="w-32">
                                <x-wireui-input label="Dosis" placeholder="Dosis del medicamento"
                                    wire:model="form.prescriptions.{{ $index }}.dosage" />
                            </div>

                            <div class="flex-1">
                                <x-wireui-input label="Frecuencia / Duración"
                                    placeholder="Frecuencia del medicamento / Duración"
                                    wire:model="form.prescriptions.{{ $index }}.frequency" />
                            </div>

                            <div class="flex-shrink-0 pt-6.5">
                                <x-wireui-mini-button sm red icon="trash"
                                    wire:click="removePrescription({{ $index }})"
                                    spinners="removePrescription({{ $index }})">

                                </x-wireui-mini-button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No hay medicamentos agregados</p>
                    @endforelse
                </div>
                <div class="mt-4">
                    <x-wireui-button outline secondary wire:click="addPrescription" spinners="addPrescription">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Agregar Medicamento
                    </x-wireui-button>
                </div>
            </x-tab-content>
        </x-tabs>
        <div class="flex justify-end mt-6">
            <x-wireui-button class="mt-6" primary wire:click="save" spinners="save">
                <i class="fa-solid fa-floppy-disk mr-2"></i>
                Guardar Consulta
            </x-wireui-button>

        </div>


    </x-wireui-card>


    {{-- modal historial paciente --}}
    <x-wireui-modal name="seeHistoryModal" max-width="7xl">
        <x-wireui-card>
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    <i class="fa-solid fa-file-medical text-indigo-600 mr-2"></i>
                    Historial Médico
                </h2>
                <p class="text-gray-600">{{ $appointment->patient->user->name }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Obra Social -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fa-solid fa-id-card text-blue-600 text-xl mr-3 mt-1"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 mb-1">Obra Social</p>
                            <p class="text-gray-700">{{ $patient->socialWork->name ?? 'No especificado' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Alergias -->
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl mr-3 mt-1"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 mb-1">Alergias</p>
                            <p class="text-gray-700">{{ $patient->allergies ?: 'No registradas' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Condiciones Crónicas -->
                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fa-solid fa-heartbeat text-orange-600 text-xl mr-3 mt-1"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 mb-1">Condiciones Crónicas</p>
                            <p class="text-gray-700">{{ $patient->chronic_conditions ?: 'Ninguna registrada' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Condiciones Genéticas -->
                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fa-solid fa-dna text-purple-600 text-xl mr-3 mt-1"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 mb-1">Condiciones Genéticas</p>
                            <p class="text-gray-700">{{ $patient->genetic_conditions ?: 'Ninguna registrada' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Antecedentes Quirúrgicos -->
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fa-solid fa-user-doctor text-teal-600 text-xl mr-3 mt-1"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 mb-1">Antecedentes Quirúrgicos</p>
                            <p class="text-gray-700">{{ $patient->surgeries_history ?: 'Ninguno registrado' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Otras Condiciones -->
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fa-solid fa-clipboard-list text-yellow-600 text-xl mr-3 mt-1"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 mb-1">Otras Condiciones</p>
                            <p class="text-gray-700">{{ $patient->other_conditions ?: 'Ninguna registrada' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Historial Familiar - Ocupa 2 columnas -->
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg md:col-span-2">
                    <div class="flex items-start">
                        <i class="fa-solid fa-users text-green-600 text-xl mr-3 mt-1"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 mb-1">Historial Familiar</p>
                            <p class="text-gray-700">{{ $patient->family_history ?: 'No registrado' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <x-slot name="footer" class="flex justify-between items-center">
                <a href="{{ route('admin.patients.edit', $patient->id) }}" target="_blank">
                    <x-wireui-button outline secondary label="Editar Paciente" icon="pencil" />
                </a>
                <x-wireui-button flat label="Cerrar" x-on:click="close" />
            </x-slot>
        </x-wireui-card>
    </x-wireui-modal>

</div>
