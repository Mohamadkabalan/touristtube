$(document).ready(function(){

Calendar.LANG("en", "English", {

        fdow: 1,                // first day of week for this locale; 0 = Sunday, 1 = Monday, etc.

        goToday: Translator.trans("Go Today"),

        today: Translator.trans("Today"),         // appears in bottom bar

        wk: "wk",

        weekend: "0,6",         // 0 = Sunday, 1 = Monday, etc.

        AM: Translator.trans("am"),

        PM: Translator.trans("pm"),

        mn : [ Translator.trans( "January" ),
               Translator.trans( "February" ),
               Translator.trans( "March" ),
               Translator.trans( "April" ),
               Translator.trans( "May" ),
               Translator.trans( "June" ),
               Translator.trans( "July" ),
               Translator.trans( "August" ),
               Translator.trans( "September" ),
               Translator.trans( "October" ),
               Translator.trans( "November" ),
               Translator.trans( "December" ) 
           ],

        smn : [ Translator.trans( "Jan" ),
                Translator.trans( "Feb" ),
                Translator.trans( "Mar" ),
                Translator.trans( "Apr" ),
                Translator.trans( "May" ),
                Translator.trans( "Jun" ),
                Translator.trans( "Jul" ),
                Translator.trans( "Aug" ),
                Translator.trans( "Sep" ),
                Translator.trans( "Oct" ),
                Translator.trans( "Nov" ),
                Translator.trans( "Dec" ) ],

        dn : [ Translator.trans( "Sunday" ),
               Translator.trans( "Monday" ),
               Translator.trans( "Tuesday" ),
               Translator.trans( "Wednesday" ),
               Translator.trans( "Thursday" ),
               Translator.trans( "Friday" ),
               Translator.trans( "Saturday" ),
               Translator.trans( "Sunday" ) ],

        sdn : [ Translator.trans( "Su" ),
                Translator.trans( "Mo" ),
                Translator.trans( "Tu" ),
                Translator.trans( "We" ),
                Translator.trans( "Th" ),
                Translator.trans( "Fr" ),
                Translator.trans( "Sa" ),
                Translator.trans( "Su" ) 
            ]
        });


});