!function(a,b){var c;c="undefined"!=typeof require&&"undefined"!=typeof exports&&"undefined"!=typeof module?require("globalize"):a.Globalize,c.addCultureInfo("fa","default",{name:"fa",englishName:"Persian",nativeName:"فارسى",language:"fa",isRTL:!0,numberFormat:{pattern:["n-"],currency:{pattern:["$n-","$ n"],".":"/",symbol:"ريال"}},calendars:{standard:{name:"Gregorian_TransliteratedFrench",firstDay:6,days:{names:["الأحد","الإثنين","الثلاثاء","الأربعاء","الخميس","الجمعة","السبت"],namesAbbr:["الأحد","الإثنين","الثلاثاء","الأربعاء","الخميس","الجمعة","السبت"],namesShort:["ح","ن","ث","ر","خ","ج","س"]},months:{names:["جانفييه","فيفرييه","مارس","أفريل","مي","جوان","جوييه","أوت","سبتمبر","اكتوبر","نوفمبر","ديسمبر",""],namesAbbr:["جانفييه","فيفرييه","مارس","أفريل","مي","جوان","جوييه","أوت","سبتمبر","اكتوبر","نوفمبر","ديسمبر",""]},AM:["ق.ظ","ق.ظ","ق.ظ"],PM:["ب.ظ","ب.ظ","ب.ظ"],eras:[{name:"م",start:null,offset:0}],patterns:{d:"MM/dd/yyyy",t:"hh:mm tt",T:"hh:mm:ss tt",f:"dddd, MMMM dd, yyyy hh:mm tt",F:"dddd, MMMM dd, yyyy hh:mm:ss tt"}},Gregorian_Localized:{firstDay:6,days:{names:["يكشنبه","دوشنبه","سه شنبه","چهارشنبه","پنجشنبه","جمعه","شنبه"],namesAbbr:["يكشنبه","دوشنبه","سه شنبه","چهارشنبه","پنجشنبه","جمعه","شنبه"],namesShort:["ی","د","س","چ","پ","ج","ش"]},months:{names:["ژانويه","فوريه","مارس","آوريل","مى","ژوئن","ژوئيه","اوت","سپتامبر","اُكتبر","نوامبر","دسامبر",""],namesAbbr:["ژانويه","فوريه","مارس","آوريل","مى","ژوئن","ژوئيه","اوت","سپتامبر","اُكتبر","نوامبر","دسامبر",""]},AM:["ق.ظ","ق.ظ","ق.ظ"],PM:["ب.ظ","ب.ظ","ب.ظ"],patterns:{d:"yyyy/MM/dd",D:"yyyy/MM/dd",t:"hh:mm tt",T:"hh:mm:ss tt",f:"yyyy/MM/dd hh:mm tt",F:"yyyy/MM/dd hh:mm:ss tt",M:"dd MMMM"}},Hijri:{name:"Hijri",firstDay:6,days:{names:["الأحد","الإثنين","الثلاثاء","الأربعاء","الخميس","الجمعة","السبت"],namesAbbr:["الأحد","الإثنين","الثلاثاء","الأربعاء","الخميس","الجمعة","السبت"],namesShort:["ح","ن","ث","ر","خ","ج","س"]},months:{names:["محرم","صفر","ربيع الأول","ربيع الثاني","جمادى الأولى","جمادى الثانية","رجب","شعبان","رمضان","شوال","ذو القعدة","ذو الحجة",""],namesAbbr:["محرم","صفر","ربيع الأول","ربيع الثاني","جمادى الأولى","جمادى الثانية","رجب","شعبان","رمضان","شوال","ذو القعدة","ذو الحجة",""]},AM:["ق.ظ","ق.ظ","ق.ظ"],PM:["ب.ظ","ب.ظ","ب.ظ"],eras:[{name:"بعد الهجرة",start:null,offset:0}],twoDigitYearMax:1451,patterns:{d:"dd/MM/yy",D:"dd/MM/yyyy",t:"hh:mm tt",T:"hh:mm:ss tt",f:"dd/MM/yyyy hh:mm tt",F:"dd/MM/yyyy hh:mm:ss tt",M:"dd MMMM"},convert:{ticks1970:621355968e5,monthDays:[0,30,59,89,118,148,177,207,236,266,295,325,355],minDate:-425216736e5,maxDate:0xe677d21fdbff,hijriAdjustment:0,toGregorian:function(a,b,c){var d=this.daysToYear(a)+this.monthDays[b]+c-1-this.hijriAdjustment,e=new Date(864e5*d-this.ticks1970);return e.setMinutes(e.getMinutes()+e.getTimezoneOffset()),e},fromGregorian:function(a){if(a<this.minDate||a>this.maxDate)return null;var b,c,d=this.ticks1970+(a-0)-6e4*a.getTimezoneOffset(),e=Math.floor(d/864e5)+1+this.hijriAdjustment,f=Math.floor(30*(e-227013)/10631)+1,g=this.daysToYear(f),h=this.isLeapYear(f)?355:354;e<g?(f--,g-=h):e===g?(f--,g=this.daysToYear(f)):e>g+h&&(g+=h,f++),c=0;for(var i=e-g;c<=11&&i>this.monthDays[c];)c++;return c--,b=i-this.monthDays[c],[f,c,b]},daysToYear:function(a){for(var b=30*Math.floor((a-1)/30),c=a-b-1,d=Math.floor(10631*b/30)+227013;c>0;)d+=this.isLeapYear(c)?355:354,c--;return d},isLeapYear:function(a){return(11*a+14)%30<11}}},Gregorian_TransliteratedEnglish:{name:"Gregorian_TransliteratedEnglish",firstDay:6,days:{names:["الأحد","الإثنين","الثلاثاء","الأربعاء","الخميس","الجمعة","السبت"],namesAbbr:["الأحد","الإثنين","الثلاثاء","الأربعاء","الخميس","الجمعة","السبت"],namesShort:["أ","ا","ث","أ","خ","ج","س"]},months:{names:["يناير","فبراير","مارس","أبريل","مايو","يونيو","يوليو","أغسطس","سبتمبر","أكتوبر","نوفمبر","ديسمبر",""],namesAbbr:["يناير","فبراير","مارس","ابريل","مايو","يونيو","يوليو","اغسطس","سبتمبر","اكتوبر","نوفمبر","ديسمبر",""]},AM:["ق.ظ","ق.ظ","ق.ظ"],PM:["ب.ظ","ب.ظ","ب.ظ"],eras:[{name:"م",start:null,offset:0}],patterns:{d:"MM/dd/yyyy",t:"hh:mm tt",T:"hh:mm:ss tt",f:"dddd, MMMM dd, yyyy hh:mm tt",F:"dddd, MMMM dd, yyyy hh:mm:ss tt"}}}})}(this);