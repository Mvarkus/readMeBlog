"use strict"

let checkedFilterCounter;

const menuTrigger   = document.querySelector('.menu-burger'); 
const filterTrigger = document.querySelector('.filter-button');
const userDropDownTrigger = document.querySelector('.user-area .drop-down-trigger');

menuTrigger.addEventListener('click', function () {
    menuTrigger.parentElement.classList.toggle('active-menu');
});

if (userDropDownTrigger !== null) {
    userDropDownTrigger.addEventListener('click', function () {
        userDropDownTrigger.parentElement.classList.toggle('active-menu');
    }); 
}

if (filterTrigger !== null) {

    filterTrigger.addEventListener('click', function () {
        filterTrigger.classList.toggle('active-filter');
    });

    const categoryFilters = document.querySelectorAll('.category-filter');
    
    for (const filter of categoryFilters) {
        filter.addEventListener('change', function () {
    
            checkedFilterCounter = 0;
    
            for (const checkbox of categoryFilters) {
                checkedFilterCounter += checkbox.checked === true ? 1 : 0;
            }
    
            if (checkedFilterCounter === 0) {
                
                if (this.checked === false) {
                    
                    this.checked = true;
                    return false;
    
                }
    
            }
    
        });
    }
}