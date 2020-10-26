<template>
    <b-container fluid="sm">
        <h2 class="mt-4">Calendar</h2>
        <hr>
        <b-card class="bg-card">
            <b-row>
                <b-col sm="4">
                    <b-form-group id="input-group-2" label="Event" label-for="input-2">
                        <b-form-input
                        id="input-2"
                        class="form-control"
                        v-model="name"
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
                            <b-form-checkbox value="Monday" v-model="daysCheckbox">Monday</b-form-checkbox>
                            <b-form-checkbox value="Tuesday" v-model="daysCheckbox">Tuesday</b-form-checkbox>
                            <b-form-checkbox value="Wednesday" v-model="daysCheckbox">Wednesday</b-form-checkbox>
                            <b-form-checkbox value="Thursday" v-model="daysCheckbox">Thursday</b-form-checkbox>
                            <b-form-checkbox value="Friday" v-model="daysCheckbox">Friday</b-form-checkbox>
                            <b-form-checkbox value="Saturday" v-model="daysCheckbox">Saturday</b-form-checkbox>
                            <b-form-checkbox value="Sunday" v-model="daysCheckbox">Sunday</b-form-checkbox>
                        </b-form-checkbox-group>
                    </b-form-group>
                    <b-button variant="primary" class="btn-primary mt-3" v-if="isSubmitting" disabled>
                        <b-spinner small type="grow"></b-spinner>
                        Loading...
                    </b-button>
                    <b-button v-else variant="primary" class="btn-primary mt-3" v-on:click="submit()">Save</b-button>
                </b-col>
                <b-col sm="8">
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
        submit() {
            this.isSubmitting = true
            axios.post("api/events/create", {
                "name" : this.name,
                "start" : this.range.start ? this.range.start.getFullYear()+"-"+this.range.start.getMonth()+"-"+this.range.start.getDate() : null,
                "end" : this.range.end ? this.range.end.getFullYear()+"-"+ (this.range.end.getDate() == 31 ? this.range.end.getMonth() + 1 : this.range.end.getMonth()) +"-"+this.range.end.getDate() : null,
                "days" : JSON.parse(JSON.stringify(this.daysCheckbox)),
            })
            .then(response => {
                console.log(response.data);
                this.isSubmitting = false
                this.getEvents()
                this.$notify({
                    group: "success",
                    type: "success",
                    position: "top right",
                    title: "Message",
                    text: response.data.message
                });
            })
            .catch(e => {
                console.log(e.response);
                this.isSubmitting = false
                this.$notify({
                    group: "success",
                    type: "error",
                    position: "top right",
                    title: "Message",
                    text: e.response.data.message
                })
            })
        },
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
            name: null,
            daysCheckbox: null,
            isSubmitting: false,
        };
  },
}
</script>
