const poster_type = document.querySelectorAll('input[type="radio"][name="poster_type"]');
const recurring_type = document.querySelectorAll('input[type="radio"][name="recurring_type"]');

const recurring = document.getElementById('recurring');
const rec_date = document.getElementById('poster_date_rec');
const nonrec_date = document.getElementById('poster_date_nonrec');
const recurring_week_fortnight = document.getElementById('recurring_week_fortnight');
const monthly_by = document.getElementById('monthly_by');

recurring.style.display = "none";
recurring_week_fortnight.style.display = "none";
rec_date.style.display = "none";
nonrec_date.style.display = "none";
monthly_by.style.display = "none";


let spinner = document.getElementById('spinner');

poster_type.forEach(function(radio) {
    radio.addEventListener('change', function() {
        if(radio.checked){
            if (radio.value === 'non_recurring') {
                nonrec_date.style.display = "block";
                rec_date.style.display = "none";
                recurring.style.display = "none";
                recurring_week_fortnight.style.display = "none";
                monthly_by.style.display = "none";
            }
            if(radio.value === 'recurring'){
                recurring.style.display = "block";
                nonrec_date.style.display = "none";

                recurring_type.forEach(function(a) {
                    a.addEventListener('change', function() {
                        if(a.checked){
                            if (a.value === 'weekly' || a.value === 'fortnightly') {
                                recurring_week_fortnight.style.display = "flex";
                                monthly_by.style.display = "none";
                            }
                            if(a.value === "monthly"){
                                monthly_by.style.display = "flex";
                            }
                            if(a.value === 'yearly'){
                                monthly_by.style.display = "none";
                            }
                            if(a.value === 'monthly' || a.value === 'yearly'){
                                recurring_week_fortnight.style.display = "none";
                            }
                            rec_date.style.display = "flex";            
                        }
                    });
                });

            }
        }
    });
});

let postForm = document.querySelector("#createPoster");
let title_err = document.querySelector("#title_err");
let file_err = document.querySelector("#file_err");
let location_err = document.querySelector("#location_err");
let description_err = document.querySelector("#description_err");
let category_err = document.querySelector("#category_err");
let poster_type_err = document.querySelector("#poster_type_err");
let recurring_type_err = document.querySelector("#recurring_type_err");
let rec_frequency_err = document.querySelector("#rec_frequency_err");
let start_time_err = document.querySelector("#start_time_err");
let end_time_err = document.querySelector("#end_time_err");
let nr_date_err = document.querySelector("#nr_date_err");
let rec_start_date_err = document.querySelector("#rec_start_date_err");
let rec_end_date_err = document.querySelector("#rec_end_date_err");
let monthly_by_err = document.querySelector("#monthly_by_err");

function createPoster(controller, redirect){
    spinner.classList.remove('d-none');
    spinner.classList.add('d-block');

    title_err.innerHTML = "";
    file_err.innerHTML = "";
    location_err.innerHTML = "";
    description_err.innerHTML = "";
    category_err.innerHTML = "";
    poster_type_err.innerHTML = "";
    recurring_type_err.innerHTML = "";
    rec_frequency_err.innerHTML = "";
    start_time_err.innerHTML = "";
    end_time_err.innerHTML = "";
    nr_date_err.innerHTML = "";
    rec_start_date_err.innerHTML = "";
    rec_end_date_err.innerHTML = "";
    monthly_by_err.innerHTML = "";
    
    let formData = new FormData(postForm);

    axios.post(controller, formData)
    .then(function (response) {
        console.log(response.data);
        let data = response.data;
        if(data){     
            let customErrors = data[0];    
            if(customErrors){
                if(customErrors.recurring_type){
                    recurring_type_err.innerHTML = customErrors.recurring_type;                
                }
                if(customErrors.frequency){
                    rec_frequency_err.innerHTML = customErrors.frequency;                
                }
                if(customErrors.time){
                    end_time_err.innerHTML = customErrors.time;                
                }
                if(customErrors.nr_date){
                    nr_date_err.innerHTML = customErrors.nr_date;                
                }
                if(customErrors.rec_start_date){
                    rec_start_date_err.innerHTML = customErrors.rec_start_date;                
                }
                if(customErrors.rec_end_date){
                    rec_end_date_err.innerHTML = customErrors.rec_end_date;                
                }
                if(customErrors.monthly_by){
                    monthly_by_err.innerHTML = customErrors.monthly_by;                
                }



            }

            if(data.success){
                setTimeout(() => {
                    window.location.assign(redirect);
                }, 2000);

            }
        }
    })
    .catch(function (error) {
        console.log(error);
        if(error.response){
            if(error.response.data.errors){
                let errorMessage = error.response.data.errors;
                if (errorMessage.title) {
                    title_err.innerHTML = errorMessage.title[0];
                }
                if (errorMessage.file) {
                    file_err.innerHTML = errorMessage.file[0];
                }
                if (errorMessage.location) {
                    location_err.innerHTML = errorMessage.location[0];
                }
                if (errorMessage.description) {
                    description_err.innerHTML = errorMessage.description[0];
                }
                if (errorMessage.category) {
                    category_err.innerHTML = errorMessage.category[0];
                }
                if (errorMessage.poster_type) {
                    poster_type_err.innerHTML = errorMessage.poster_type[0];
                }
                if (errorMessage.start_time) {
                    start_time_err.innerHTML = errorMessage.start_time[0];
                }
                if (errorMessage.end_time) {
                    end_time_err.innerHTML = errorMessage.end_time[0];
                }


            }
        }
    })
    .finally(() => {
        spinner.classList.remove('d-block');
        spinner.classList.add('d-none');
    });
}