import{_ as $}from"./CardComponent.vue_vue_type_style_index_0_lang-5b0029ab.js";import{_ as x,a as C}from"./CardDivider.vue_vue_type_style_index_0_lang-d3f03ffa.js";import{_ as K}from"./CardLinkBlock.vue_vue_type_script_setup_true_lang-d44c3c52.js";import{d,g as i,k as S,v as b,o as v,c as A,a,u as n,x as c,m as L,q as u,b as j,w as B}from"./index-8b280c30.js";import{u as F,_,a as I}from"./useAxios-5e6bb7b6.js";import{n as p,p as f}from"./LoaderComponent.vue_vue_type_style_index_0_lang-07c726db.js";import"./LinkComponent.vue_vue_type_script_setup_true_lang-09c82395.js";import"./_plugin-vue_export-helper-c27b6911.js";const N=["onSubmit"],P=d({__name:"LoginFormContainer",setup(h){const s=i(""),r=e=>{s.value=e.trim(),t.value&&(t.value="")},o=i(""),g=e=>{o.value=e.trim(),t.value&&(t.value="")},t=i(""),{response:k,isLoading:l,makeApiCall:w}=F(),y=S(()=>s.value.length>2&&o.value.length>7&&!t.value&&!l.value);function m(){w({method:"POST",url:"/users/login",data:{username:s.value,password:o.value}})}return b(k,e=>{e!=null&&e.token?(localStorage.setItem("token",e.token),localStorage.setItem("user",e.user),L.push("/")):e!=null&&e.error&&(t.value=e.error)}),u(p,[s,r]),u(f,[o,g]),(e,V)=>(v(),A("form",{class:"form",onSubmit:c(m,["prevent"])},[a(_,{name:"name",title:"Имя пользователя",error:t.value,injectionKey:n(p)},null,8,["error","injectionKey"]),a(_,{name:"password",title:"Пароль",injectionKey:n(f)},null,8,["injectionKey"]),a(I,{title:"Войти",type:"submit",isFullWidth:"",isActive:n(y),isLoading:n(l),onClick:c(m,["prevent"])},null,8,["isActive","isLoading","onClick"])],40,N))}}),G=d({__name:"LoginView",setup(h){return(s,r)=>(v(),j($,null,{default:B(()=>[a(K),a(x,{title:"Вход в систему"}),a(C),a(P)]),_:1}))}});export{G as default};
