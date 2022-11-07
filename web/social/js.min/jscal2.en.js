$(document).ready(function(){

Calendar.LANG("en", "English", {

        fdow: 1,                // first day of week for this locale; 0 = Sunday, 1 = Monday, etc.

        goToday: t("Go Today"),

        today: t("Today"),         // appears in bottom bar

        wk: "wk",

        weekend: "0,6",         // 0 = Sunday, 1 = Monday, etc.

        AM: "am",

        PM: "pm",

        mn : [ t( "January" ),
               t( "February" ),
               t( "March" ),
               t( "April" ),
               t( "May" ),
               t( "June" ),
               t( "July" ),
               t( "August" ),
               t( "September" ),
               t( "October" ),
               t( "November" ),
               t( "December" ) 
           ],

        smn : [ t( "Jan" ),
                t( "Feb" ),
                t( "Mar" ),
                t( "Apr" ),
                t( "May" ),
                t( "Jun" ),
                t( "Jul" ),
                t( "Aug" ),
                t( "Sep" ),
                t( "Oct" ),
                t( "Nov" ),
                t( "Dec" ) ],

        dn : [ t( "Sunday" ),
               t( "Monday" ),
               t( "Tuesday" ),
               t( "Wednesday" ),
               t( "Thursday" ),
               t( "Friday" ),
               t( "Saturday" ),
               t( "Sunday" ) ],

        sdn : [ t( "Su" ),
                t( "Mo" ),
                t( "Tu" ),
                t( "We" ),
                t( "Th" ),
                t( "Fr" ),
                t( "Sa" ),
                t( "Su" ) 
            ]
        });


});