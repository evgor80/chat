import{u as _}from"./useAxios-5e6bb7b6.js";import{g as s,v as P,k as a,o as k,c as C}from"./index-8b280c30.js";import{_ as x}from"./_plugin-vue_export-helper-c27b6911.js";function y(o){const n="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";return Array(o).fill("").map(()=>n[Math.floor(Math.random()*n.length)]).join("")}function G(o){const n=s(""),r=s(""),t=s(""),{response:l,isLoading:c,makeApiCall:u}=_();P(n,async e=>{e.length>2&&u({method:"POST",url:`/${o}s/name`,data:{name:e}})});function i(e){n.value=e.trim()}function m(e){t.value=e.trim()}function d(e){r.value=e.trim()}function f(){const e=y(12);r.value=e,t.value=e}function h(e){u({method:"POST",url:`/${o}s`,data:e})}const g=a(()=>r.value===t.value),v=a(()=>{var e;return n.value.length>0&&n.value.length<3?"Должно содержать минимум 3 символа":((e=l.value)==null?void 0:e.status)==="failed"?"Это имя уже занято":""}),p=a(()=>r.value.length>0&&r.value.length<8?"Пароль должен содержать минимум 8 символов":""),w=a(()=>t.value.length>0&&!g.value?"Пароли не совпадают":"");return{name:n,password:r,confirmPassword:t,response:l,handlleNameChange:i,handllePasswordChange:d,handlleConfirmPasswordChange:m,handlePassworgGeneratorClick:f,register:h,nameError:v,passwordError:p,confirmPasswordError:w,isLoading:c}}const B={};function E(o,n){return k(),C("button",{onClick:n[0]||(n[0]=r=>o.$emit("clicked")),type:"button",class:"form__password-generator-button"}," Сгенерировать пароль автоматически ")}const M=x(B,[["render",E]]);export{M as F,G as u};