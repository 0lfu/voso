$(document).ready(function () {
    const style = document.createElement('style');
    style.setAttribute("id", "multiselect_dropdown_styles");
    style.innerHTML = `
.multiselect-dropdown{
  display: inline-block;
  width: 100%;
  padding: 5px;
  background-color: #303030;
  border: 1px solid #303030;
  position: relative;
}
.multiselect-dropdown span.optext, .multiselect-dropdown span.placeholder{
  margin-right:0.5em; 
  margin-bottom:2px;
  padding:1px 0; 
  display:inline-block;
}
.multiselect-dropdown span.optext{
  background-color: var(--accent1);
  padding: 4px 10px;
  border-radius: 5px;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  cursor: default;
}
.multiselect-dropdown span.optext .optdel {
float: right;
  margin-left: 20px;
  font-size: 20px;
  cursor: pointer;
  color: white;
}
.multiselect-dropdown span.optext .optdel:hover { 
  color: rgba(255,255,255,0.7);
}
.optext:has(.optdel:hover) {
  color: rgba(255,255,255,0.7);
}
.multiselect-dropdown span.placeholder{
  color:#ced4da;
}
.multiselect-dropdown-list-wrapper{
  z-index: 100;
  padding:2px;
  border: none;
  display: none;
  margin: -1px;
  position: absolute;
  top:0;
  left: 0;
  right: 0;
  background: #303030;
}
.multiselect-dropdown-list-wrapper .multiselect-dropdown-search{
  margin-bottom:5px;
}
.multiselect-dropdown-search:focus{
  border: none!important;
}
.multiselect-dropdown-list{
  padding:2px;
  height: 15rem;
  overflow-y:auto;
  overflow-x: hidden;
}
.multiselect-dropdown-list::-webkit-scrollbar {
  width: 6px;
}
.multiselect-dropdown-list::-webkit-scrollbar-thumb {
  background-color: #bec4ca;
}

.multiselect-dropdown-list div{
  padding: 5px;
  display:flex;
  align-items: center;
}
.multiselect-dropdown-list input{
  height: 1.15em;
  width: 1.15em;
  margin-right: 0.35em;  
}
.multiselect-dropdown-list div.checked{
}
.multiselect-dropdown-list div:hover{
  background-color: #555555;
}
.multiselect-dropdown span.maxselected {width:100%;}
.multiselect-dropdown-all-selector {border-bottom:solid 1px #999;}
`;
    document.head.appendChild(style);

    function MultiselectDropdown(options) {
        const config = {
            search: true,
            height: '15rem',
            placeholder: '',
            txtSelected: 'selected',
            txtAll: 'All',
            txtRemove: 'Remove',
            txtSearch: 'search...',
            ...options
        };

        function newEl(tag, attrs) {
            const e = document.createElement(tag);
            if (attrs !== undefined) Object.keys(attrs).forEach(k => {
                if (k === 'class') {
                    Array.isArray(attrs[k]) ? attrs[k].forEach(o => o !== '' ? e.classList.add(o) : 0) : (attrs[k] !== '' ? e.classList.add(attrs[k]) : 0)
                } else if (k === 'style') {
                    Object.keys(attrs[k]).forEach(ks => {
                        e.style[ks] = attrs[k][ks];
                    });
                } else if (k === 'text') {
                    attrs[k] === '' ? e.innerHTML = '&nbsp;' : e.innerText = attrs[k]
                } else e[k] = attrs[k];
            });
            return e;
        }


        document.querySelectorAll("select[multiple]").forEach((el, k) => {

            const div = newEl('div', {class: 'multiselect-dropdown'});
            el.style.display = 'none';
            el.parentNode.insertBefore(div, el.nextSibling);
            const listWrap = newEl('div', {class: 'multiselect-dropdown-list-wrapper'});
            const list = newEl('div', {class: 'multiselect-dropdown-list', style: {height: config.height}});
            const search = newEl('input', {
                class: ['multiselect-dropdown-search'].concat([config.searchInput?.class ?? 'form-control']),
                style: {
                    width: '100%',
                    display: el.attributes['multiselect-search']?.value === 'true' ? 'block' : 'none'
                },
                placeholder: config.txtSearch
            });
            listWrap.appendChild(search);
            div.appendChild(listWrap);
            listWrap.appendChild(list);

            el.loadOptions = () => {
                list.innerHTML = '';

                if (el.attributes['multiselect-select-all']?.value == 'true') {
                    var op = newEl('div', {class: 'multiselect-dropdown-all-selector'})
                    var ic = newEl('input', {type: 'checkbox'});
                    op.appendChild(ic);
                    op.appendChild(newEl('label', {text: config.txtAll}));

                    op.addEventListener('click', () => {
                        op.classList.toggle('checked');
                        op.querySelector("input").checked = !op.querySelector("input").checked;

                        const ch = op.querySelector("input").checked;
                        list.querySelectorAll(":scope > div:not(.multiselect-dropdown-all-selector)")
                            .forEach(i => {
                                if (i.style.display !== 'none') {
                                    i.querySelector("input").checked = ch;
                                    i.optEl.selected = ch
                                }
                            });

                        el.dispatchEvent(new Event('change'));
                    });
                    ic.addEventListener('click', (ev) => {
                        ic.checked = !ic.checked;
                    });
                    el.addEventListener('change', (ev) => {
                        let itms = Array.from(list.querySelectorAll(":scope > div:not(.multiselect-dropdown-all-selector)")).filter(e => e.style.display !== 'none')
                        let existsNotSelected = itms.find(i => !i.querySelector("input").checked);
                        if (ic.checked && existsNotSelected) ic.checked = false;
                        else if (ic.checked == false && existsNotSelected === undefined) ic.checked = true;
                    });

                    list.appendChild(op);
                }

                Array.from(el.options).map(o => {
                    const op = newEl('div', {class: o.selected ? 'checked' : '', optEl: o});
                    const ic = newEl('input', {type: 'checkbox', checked: o.selected});
                    op.appendChild(ic);
                    op.appendChild(newEl('label', {text: o.text}));

                    op.addEventListener('click', () => {
                        op.classList.toggle('checked');
                        op.querySelector("input").checked = !op.querySelector("input").checked;
                        op.optEl.selected = !!!op.optEl.selected;
                        el.dispatchEvent(new Event('change'));
                    });
                    ic.addEventListener('click', (ev) => {
                        ic.checked = !ic.checked;
                    });
                    o.listitemEl = op;
                    list.appendChild(op);
                });
                div.listEl = listWrap;

                div.refresh = () => {
                    div.querySelectorAll('span.optext, span.placeholder').forEach(t => div.removeChild(t));
                    const sels = Array.from(el.selectedOptions);
                    if (sels.length > (el.attributes['multiselect-max-items']?.value ?? 7)) {
                        div.appendChild(newEl('span', {
                            class: ['optext', 'maxselected'],
                            text: sels.length + ' ' + config.txtSelected
                        }));
                    } else {
                        sels.map(x => {
                            const c = newEl('span', {class: 'optext', text: x.text, srcOption: x});
                            if ((el.attributes['multiselect-hide-x']?.value !== 'true'))
                                c.appendChild(newEl('span', {
                                    class: 'optdel',
                                    text: '🗙',
                                    title: config.txtRemove,
                                    onclick: (ev) => {
                                        c.srcOption.listitemEl.dispatchEvent(new Event('click'));
                                        div.refresh();
                                        ev.stopPropagation();
                                    }
                                }));

                            div.appendChild(c);
                        });
                    }
                    if (0 == el.selectedOptions.length) div.appendChild(newEl('span', {
                        class: 'placeholder',
                        text: el.attributes['placeholder']?.value ?? config.placeholder
                    }));
                };
                div.refresh();
            }
            el.loadOptions();

            search.addEventListener('input', () => {
                list.querySelectorAll(":scope div:not(.multiselect-dropdown-all-selector)").forEach(d => {
                    const txt = d.querySelector("label").innerText.toUpperCase();
                    d.style.display = txt.includes(search.value.toUpperCase()) ? 'block' : 'none';
                });
            });

            div.addEventListener('click', () => {
                div.listEl.style.display = 'block';
                search.focus();
                search.select();
            });

            document.addEventListener('click', function (event) {
                if (!div.contains(event.target)) {
                    listWrap.style.display = 'none';
                    div.refresh();
                }
            });
        });
    }

    window.addEventListener('load', () => {
        MultiselectDropdown(window.MultiselectDropdownOptions);
    });
});