<template>
    <b-container fluid class="p-4">
        <h2>Calendar</h2>
        <hr>
        <b-card class="bg-card p-2">
            <b-row>
                <b-col cols="4">
                    <b-form-group id="input-group-2" label="Event" label-for="input-2">
                        <b-form-input
                        id="input-2"
                        class="form-control"
                        required
                        ></b-form-input>
                    </b-form-group>
                    <b-form-group id="input-group-2" label="Select Range" label-for="input-3">  
                        <vc-date-picker v-model="range" :masks="masks" :min-date="datePicker.min" :max-date="datePicker.max" is-range>
                            <template v-slot="{ inputValue, inputEvents }">
                                <div class="flex justify-center items-center">
                                <input
                                    :value="inputValue.start"
                                    v-on="inputEvents.start"
                                    class="border px-5 py-1 w-32 rounded focus:outline-none focus:border-indigo-300"
                                />
                                <svg
                                    class="w-5 h-4 mx-2"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                                    />
                                </svg>
                                <input
                                    :value="inputValue.end"
                                    v-on="inputEvents.end"
                                    class="border px-5 py-1 w-32 rounded focus:outline-none focus:border-indigo-300"
                                />
                                </div>
                            </template>
                        </vc-date-picker>
                    </b-form-group>
                    <b-form-group id="input-group-4">
                        <b-form-checkbox-group id="checkboxes-4">
                            <b-form-checkbox value="m">Monday</b-form-checkbox>
                            <b-form-checkbox value="tu">Tuesday</b-form-checkbox>
                            <b-form-checkbox value="w">Wednesday</b-form-checkbox>
                            <b-form-checkbox value="th">Thursday</b-form-checkbox>
                            <b-form-checkbox value="f">Friday</b-form-checkbox>
                            <b-form-checkbox value="sa">Saturday</b-form-checkbox>
                            <b-form-checkbox value="su">Sunday</b-form-checkbox>
                        </b-form-checkbox-group>
                    </b-form-group>
                    <b-button variant="primary" class="btn-primary mt-3">Save</b-button>
                </b-col>
                <b-col cols="8" class="px-5">
                    <h3>{{ month }}</h3>
                    <div class="list-content" id="custom-scrollbar">
                        <b-list-group v-for="index in datePicker.max.getDate()" :key="index">
                            <b-list-group-item 
                            v-bind:variant="checkEvents(index) ? 'success' : ''"> 
                                <div class="d-flex justify-content-start">
                                    <div class="date-content">
                                        <span class="count">{{ index }}</span>
                                    </div>
                                    <div class="day-content">
                                        <span class="count">{{ new Date(date.getFullYear(), date.getMonth(), index).toLocaleDateString(undefined, { weekday: 'long' }) }}</span>
                                    </div>
                                     <div class="event-content">
                                        <span class="event-details" v-if="checkEvents(index)">{{ events.name }}</span>
                                    </div>
                                </div>
                            </b-list-group-item>
                        </b-list-group>
                    </div>
                </b-col>
            </b-row>
        </b-card>
    </b-container>
</template>

<script>
export default {
    name: 'index',
    created() {
        this.getEvents()
    },
    methods: {
        getEvents() {
            axios.post("/api/events/display", {
            })
            .then(response => {
                this.events = response.data
            })
            .catch(e => {
                console.log(e);
            })
        },
        checkEvents(index) {
            if (index >= new Date(this.events.min).getDate() && 
                index <= new Date(this.events.max).getDate() &&
                this.events.days.includes(new Date(this.date.getFullYear(), this.date.getMonth(), index).toLocaleDateString(undefined, { weekday: 'long' }))) {
                return true;
            } else {
                return false;
            }
        },
    },
    data() {
        var date = new Date();
        return {
            date: date,
            month: date.toLocaleString('default', { month: 'long' }),
            datePicker: {
                min: new Date(date.getFullYear(), date.getMonth(), 1),
                max: new Date(date.getFullYear(), date.getMonth() + 1, 0),
            },
            range: {
                start: null,
                end: null,
            },
            masks: {
                input: 'YYYY-MM-DD',
            },
            //
            events: {},
        };
  },
}
</script>
