// For Add or Remove Flight Multi City Option Start
$(document).ready(function () {
    $("#addMulticityRow").on('click', (function () {
        let a = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);

        if (document.querySelectorAll('.multi_city_form').length === 3) {
            alert("Max City Limit Reached!!")
            return;
        }
        $(".multi_city_form_wrapper").append(`<div class="multi_city_form mt-0 mt-md-0 mt-lg-0 mt-xl-2">
        <div class="row">
            <div class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                <div class="form-group">
                    <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                    <input type="text" class="form-control ps-5" id="multiOrigin"
                        placeholder="Origin">
                </div>
            </div>
            <div
                class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2">
                <div class="form-group">
                    <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                    <input type="text" class="form-control ps-5"
                        id="multiDestination" placeholder="Destination">
                </div>
            </div>
            <div
                class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2 pe-xl-2">
                <div class="form-control form-group d-flex">
                    <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                    <span class="dep-date-input">
                        <input type="text" class="cal-input"
                            placeholder="Depart Date" id="datepicker3">
                    </span>
                </div>
            </div>
            <div class="col-lg-12 col-xl-3">
                <div class="multi_form_remove">
                    <button type="button" id="remove_multi_city" class="btn">Remove</button>
                </div>
            </div>
        </div>
    </div>`);

    }))
    // Remove Button Click 
    $(document).on('click', (function (e) {
        if (e.target.id === "remove_multi_city") {
            $(e.target).parent().closest('.multi_city_form').remove()
        }
    })

    )

});