javascript:(function(){function make(t){if(null==t.getAttribute("data-prev-action")){var e=t.action.split("?")[1];console.log(e),t.setAttribute("data-prev-action",t.action),t.action="http://showme.aurer.co.uk"+(e?"?"+e:""),t.target="_blank",t.style.border="2px solid #1BA5E0",t.style.boxShadow=" 0 0 15px 4px #1BA5E0";var o=document.createElement("input");o.type="hidden",o.name="form-action",o.value=t.getAttribute("data-prev-action"),t.appendChild(o)}else unmake(t)}function unmake(t){t.action=t.getAttribute("data-prev-action"),t.removeAttribute("data-prev-action"),t.removeAttribute("target"),t.style.outline="none",t.style.boxShadow="none",document.querySelector('input[name="*action"]').remove()}var forms=document.querySelectorAll("form");for(i=0;i<forms.length;i++)make(forms[i]);})();