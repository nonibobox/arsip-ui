import e from"../core/Plugin";export default class t extends e{constructor(e){super(e);this.opts=Object.assign({},{autoPlay:true},e)}install(){this.fields=Object.keys(this.core.getFields());if(this.opts.autoPlay){this.play()}}play(){return this.animate(0)}animate(e){if(e>=this.fields.length){return Promise.resolve(e)}const t=this.fields[e];const s=this.core.getElements(t)[0];const i=s.getAttribute("type");const r=this.opts.data[t];if("checkbox"===i||"radio"===i){s.checked=true;s.setAttribute("checked","true");return this.core.revalidateField(t).then(t=>this.animate(e+1))}else if(!r){return this.animate(e+1)}else{return new Promise(i=>new Typed(s,{attr:"value",autoInsertCss:true,bindInputFocusEvents:true,onComplete:()=>{i(e+1)},onStringTyped:(e,i)=>{s.value=r[e];this.core.revalidateField(t)},strings:r,typeSpeed:100})).then(e=>this.animate(e))}}}