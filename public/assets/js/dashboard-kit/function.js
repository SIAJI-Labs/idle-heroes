"use strict";
const APP_TIMEZONE = 'GMT+0700';

/**
 * Print Indonesia default amount format
 * 
 * @param {*} angka 
 * @param {*} prefix 
 * @returns 
 */
 function numberFormat(angka, prefix = 'Rp'){
    let negative = angka < 0 ? true : false;

    angka = Math.round(angka * 100) / 100;
    let split = angka.toString().split('.');
    let decimal = 0;
    if(split.length > 1){
        angka = split[0];
        decimal = split[1];
    }
    var	reverse = angka.toString().split('').reverse().join(''),
	rupiah 	= reverse.match(/\d{1,3}/g);
    rupiah	= rupiah.join('.').split('').reverse().join('');

    if(split.length > 1){
        rupiah += `,${decimal}`;
    }
    
    return `${(prefix == undefined ? `${negative ? '-' : ''}${rupiah}` : `${prefix} ${negative ? '-' : ''}${rupiah}`)}`;
}

/**
 * Apply timezone field to all form element
 * 
 */
const applyTimezoneField = (apply = null) => {
    // Get all Form Element
    let formEl = document.getElementsByTagName('form');
    let date = new Date();

    let defaultTimezone = new Date(`${date} ${APP_TIMEZONE}`);
    if(navigator.userAgent.indexOf("Safari") != -1) {
        // Fix date format on safari browser
        defaultTimezone = new Date(`${moment(date).format('ddd, DD MMM YYYY HH:mm:ss')} ${APP_TIMEZONE}`);
    }
    // Get timezone offset
    const tzOffset = defaultTimezone.getTimezoneOffset();
    if(apply === true){
        return tzOffset;
    }

    for(let el of formEl){
        // Apply timezone offset at the begining of form element
        el.insertAdjacentHTML('afterbegin', `
            <input type="hidden" name="_timezone" value="${tzOffset}" readonly>
        `);
    }
}

/**
 * Convert default new Date() format to MomentJs mormat
 * 
 * @param {*} date 
 * @param {*} format 
 * @param {*} timezone 
 * @returns 
 */
 function momentDateTime(date, format, timezone = null, offsetFormat = true){
    let defaultTimezone = new Date(`${date} ${APP_TIMEZONE}`);
    if(navigator.userAgent.indexOf("Safari") != -1) {
        // Fix date format on safari browser
        defaultTimezone = new Date(`${moment(date).format('ddd, DD MMM YYYY HH:mm:ss')} ${APP_TIMEZONE}`);
    }
    const tzOffset = defaultTimezone.getTimezoneOffset();

    // Get Timezone symbol (ex: UTC+7 etc)
    if(timezone !== null){
        let offsetSymbol = tzOffset < 0 ? '+' : '-';
        let offsetNum = tzOffset / 60;
        if(offsetNum < 0){
            offsetNum *= -1;
        }

        if(offsetFormat){
            // Apply symbol to existing string format
            format += ` (UTC${offsetSymbol}${offsetNum})`;
        }
    }

    return moment(convertDateTime(defaultTimezone)).format(format);
}

/**
 * Convert Timezone
 * Convert specific date (default is UTC+7) to match user timezone
 * 
 * @param {*} date 
 * @param {*} timezone 
 * @returns 
 */
 function convertDateTime(date, timezone = null){
    if(timezone === null){
        // Get user Timezone if timezone is not specified
        timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    }

    return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: timezone}));
}