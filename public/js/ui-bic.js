"use strict";

/*-----------------------------------------------------
 *                     Modales
 *-----------------------------------------------------*/
// Variables
let modalTriggers;

/**
 * Bootstrap de ventanas modales
 */
function modal() {
   // Inicializacion de variables
   modalTriggers = document.querySelectorAll('button[data-toggle="modal"]');

   // Comportamiento de componentes
   modalTriggers.forEach(trig => {
      trig.addEventListener('click', (evt) => {
         showModal(trig.getAttribute('data-target'));
      }, { passive: true, capture: false });
   });
}

/**
 * Despacha el evento de abrir ventana modal.
 * @param {string} querySelector querySelector de la ventana modal
 */
export function showModal(querySelector) {
   let body = document.body;

   let modal = document.querySelector(querySelector);
   let dialog = modal.querySelector('.modal-dialog');
   let btnClose = dialog.firstElementChild.firstElementChild.lastElementChild;

   body.classList.add('modal-open');
   modal.style.display = 'block';
   modal.scrollTo(0, 0);

   setTimeout(() => {
      modal.style.opacity = '1';
      dialog.style.transform = 'translate(0, 0)';
   }, 100);

   btnClose.addEventListener('click', () => {
      hideModal(querySelector);
   }, { passive: true, capture: false, once: true });
}

/**
 * Despacha el evento de cerrar ventana modal para el boton cerrar.
 * @param {string} querySelector querySelector de la ventana modal
 */
export function hideModal(querySelector) {
   let body = document.body;

   let modal = document.querySelector(querySelector);
   let dialog = modal.querySelector('.modal-dialog');
   let btnClose = dialog.firstElementChild.firstElementChild.lastElementChild;

   modal.style.opacity = '0';
   dialog.style.transform = 'translate(0, -50px)';

   setTimeout(() => {
      modal.style.display = 'none';
      body.classList.remove('modal-open');
   }, 300);

   btnClose.removeEventListener('click', () => {
      hideModal(querySelector);
   }, false);
}

/*-----------------------------------------------------
 *                     Popovers
 *-----------------------------------------------------*/
// Variables
let buttonPopTriggers;

/**
 * Bootstrap de popovers
 */
function popover() {
   // Inicializacion de variables
   buttonPopTriggers = document.querySelectorAll('[data-toggle=popover]');

   // Comportamiento de componentes
   buttonPopTriggers.forEach(trig => {
      trig.addEventListener("click", (evt) => {
         evt.preventDefault();

         try {
            let pop = document.querySelector(trig.getAttribute('data-target'));

            if (pop == null)
               throw new Error(`Valor del atributo [data-target=${trig.getAttribute('data-target')}] no coincide con id del popover.`);

            pop.style.display = 'block';

            const trigOffset = offset(trig);
            const popuOffset = offset(pop);

            pop.style.top = (trig.offsetTop - trig.scrollTop + trig.clientTop + trig.clientHeight + 2) + "px";

            pop.style.left =
               (trigOffset.width < popuOffset.width) ?
                  (trig.offsetLeft - trig.scrollLeft + trig.clientLeft - popuOffset.width + trigOffset.width) + "px" :
                  (trig.offsetLeft - trig.scrollLeft + trig.clientLeft - popuOffset.width / 2 + trigOffset.width / 2) + "px";

         } catch (error) {
            console.error(error);
         }

      }, {
         capture: false
      });

      trig.addEventListener('focusout', (evt) => {
         evt.preventDefault();

         try {
            let pop = document.querySelector(trig.getAttribute('data-target'));

            if (pop == null)
               throw new Error(`Valor del atributo [data-target=${trig.getAttribute('data-target')}] no coincide con id del popover.`);

            pop.style.display = 'none';
         } catch (error) {
            console.error(error);
         }

      }, {
         capture: false
      });
   });
}

/*-----------------------------------------------------
 *                  Selects nativos
 *-----------------------------------------------------*/
// Variables
let selects;

/**
 * Bootstrap de selects nativos
 */
function select() {
   // Inicializacion de variables
   selects = document.querySelectorAll('select[class=list-data]');

   // Comportamiento de componentes
   selects.forEach(select => {
      select.addEventListener('focusin', (evt) => {
         evt.preventDefault();

         try {
            let attr = select.getAttribute('data-select');
            let btn = document.querySelector(`button[class=list-button][data-select=${attr}]`);

            if (btn == null)
               throw new Error(`Atributo [data-select=${attr}] no encontrado en botón.`);

            btn.style.border = '1px solid #4A4C7D';
         } catch (error) {
            console.error(error);
         }

      }, false);

      select.addEventListener('focusout', (evt) => {
         evt.preventDefault();

         try {
            let attr = select.getAttribute('data-select');
            let btn = document.querySelector(`button[class=list-button][data-select=${attr}]`);

            if (btn == null)
               throw new Error(`Atributo [data-select=${attr}] no encontrado en botón.`);

            btn.style.border = '1px solid #dbdbdb';
         } catch (error) {
            console.error(error);
         }

      }, false);
   });
}

/*-----------------------------------------------------
 *                      Tooltips
 *-----------------------------------------------------*/

// Variables
let tipsTrigger, count = 0;

/**
 * Bootstrap de tooltips
 */
function tooltip() {
   tipsTrigger = document.querySelectorAll('[data-toggle=tooltip]');

   tipsTrigger.forEach(trig => {
      count++;
      const dataTitle = trig.getAttribute('title');
      trig.setAttribute('data-title', dataTitle);
      trig.setAttribute('aria-describedby', `tooltip${count}`);
      trig.removeAttribute('title');

      const tooltip = document.createElement('span');
      tooltip.setAttribute('data-placement', trig.getAttribute('data-placement'));
      tooltip.setAttribute('id', `tooltip${count}`);
      tooltip.setAttribute('role', 'tooltip');
      tooltip.classList.add('tooltip');
      tooltip.innerHTML = dataTitle;
      document.body.append(tooltip);


      trig.addEventListener('mouseover', (evt) => {
         evt.preventDefault();
         tooltip.style.display = 'block';

         const trigPosition = offset(trig);
         const tipPosition = offset(tooltip);
         const dataPlacement = trig.getAttribute('data-placement');

         setTimeout(() => { tooltip.style.opacity = '1'; }, 100);

         switch (dataPlacement) {
            // ------Tooltips Arriba------
            case 'top':
               tooltip.style.top = `${trigPosition.top - tipPosition.height - 8}px`;
               if (
                  trigPosition.width < tipPosition.width &&
                  (trigPosition.left > (tipPosition.width / 2 - trigPosition.width / 2))
               ) {
                  tooltip.style.left
                     = `${trigPosition.left - (tipPosition.width / 2 - trigPosition.width / 2)}px`;
               } else {
                  tooltip.style.left
                     = `${trigPosition.left + (trigPosition.width / 2 - tipPosition.width / 2)}px`;
               }
               tooltip.style.setProperty('--left-position', `${tipPosition.width / 2 - 6}px`);
               break;

            // ------Tooltips Abajo------
            case 'bottom':
               tooltip.style.top = `${trigPosition.top + 40}px`;
               if (
                  trigPosition.width < tipPosition.width &&
                  (trigPosition.left > (tipPosition.width / 2 - trigPosition.width / 2))
               ) {
                  tooltip.style.left
                     = `${trigPosition.left - (tipPosition.width / 2 - trigPosition.width / 2)}px`;
               } else {
                  tooltip.style.left
                     = `${trigPosition.left + (trigPosition.width / 2 - tipPosition.width / 2)}px`;
               }
               tooltip.style.setProperty('--left-position', `${tipPosition.width / 2 - 6}px`);
               break;

            // ------Tooltips Izquierda------
            case 'left':
               tooltip.style.top = `${trigPosition.top}px`;
               if (
                  tipPosition.width > trigPosition.width
               ) {
                  tooltip.style.left
                     = `${trigPosition.left - tipPosition.width - 8}px`;
               }

               if (
                  tipPosition.width < trigPosition.width
               ) {
                  tooltip.style.left
                     = `${trigPosition.left - trigPosition.width - (tipPosition.width - trigPosition.width) - 8}px`;
               }
               break;

            // ------Tooltips Derecha------
            case 'right':
               tooltip.style.top = `${trigPosition.top}px`;
               if (
                  tipPosition.width > trigPosition.width
               ) {
                  tooltip.style.left
                     = `${trigPosition.left + tipPosition.width + (trigPosition.width - tipPosition.width) + 8}px`;
               }

               if (
                  tipPosition.width < trigPosition.width
               ) {
                  tooltip.style.left
                     = `${trigPosition.left + trigPosition.width + 8}px`;
               }
               break;
            default:
               break;
         }
      }, false);

      trig.addEventListener('mouseleave', (evt) => {
         evt.preventDefault();
         tooltip.style.display = 'none';
         tooltip.style.opacity = '0';
      }, false);
   });
}

/**
 * Retorna la posicion absoluta de un elemento
 * @param {HTMLElement} element Elemento HTML
 * @returns {Object} Objeto con la posicion absoluta y dimension del elemento
 */
function offset(element) {
   const rect = element.getBoundingClientRect();
   return {
      width: rect.width,
      height: rect.height,
      left: rect.left + window.scrollX,
      right: rect.right,
      top: rect.top + window.scrollY,
      bottom: rect.bottom,
      x: rect.x,
      y: rect.y
   };
}

/*-----------------------------------------------------
 *            Campo de busqueda
 *-----------------------------------------------------*/

/**
 * Clase Search personalizado, agrega propiedades como componente.
 */
export class Search {

   // Variables privadas
   #options;
   #buttonItems;
   #keyboardCode;
   #mouseClickType;
   #optionIndex;
   #itemIndex;
   #dataLength;

   /**
    * Constructor de la clase Search.
    * @param {string} search id del search del DOM 
    */
   constructor(search) {

      /**
       * Elemento input tipo search personalizado para ser un campo de busqueda
       */
      this.search = document.getElementById(search);

      /**
       * Elemento span personalizado para ser una listbox
       */
      this.listbox = document.querySelector(this.search.getAttribute('data-target'));

      /**
       * Elemento ul que esta dentro del listbox y contiene elementos li
       */
      this.menu = this.listbox.firstElementChild;

      /**
       * Arreglo de objetos que contiene el texto de cada item del listbox
       */
      this.data = [];

      /**
       * Arreglo de los elementos hijos (li) de this.menu (ul)
       */
      this.#options = [];

      /**
       * Arreglo de las etiquetas html <buttons> o <a> que contienen la clase 'btn-menu-item'
       */
      this.#buttonItems = [];

      /**
       * Codigo de tecla obtenido por el evento keydown en formato string
       */
      this.#keyboardCode = '';

      /**
       * Codigo en formato de numero entero de cada uno de los botones del mouse presionado
       */
      this.#mouseClickType;

      /**
       * Indice del arreglo de las opciones (this.#options),
       * es parte del atributo aria-activedescendant en el campo de busqueda
       * establece el estilo de cada item de la lista cuando se selecciona con
       * las flechas de navegacion del teclado
       */
      this.#optionIndex = 0;

      /**
       * Indice de cada item (li) de this.menu establecido en el atributo id=`option-${itemIndex}`
       */
      this.#itemIndex = 1;

      /**
       * Tamaño del arreglo de objetos de this.data
       */
      this.#dataLength = 0;

      this.#bind();
   }

   /**
    * Enlazamiento de propiedades iniciales
    * de los componentes.
    */
   #bind() {
      this.#onFocusOutDefault();
      this.#onKeydownDefualt();
      this.#onInputSearchDefault();
      this.#onFocusInDefault();
      this.#onMouseDownDefault();
      this.#onWindowResizeDefault();
      this.#onWindowLoadDefault();
      this.#onDocumentKeydownDefualt();
   }

   /**
    * El metodo permite el registro de eventos el Listener al search dinamicamente.
    * @param {string} type Especifica el Event.type asociado con el evento registrado por el usuario.
    * @param {Event} listener Funcion o callback como parametro.
    * @param {boolean} useCapture Si true, useCapture indica que el usuario
    *  desea agregar el listener del evento solo para la fase de captura,
    *  es decir, este listener del evento no se activará durante las fases objetivo
    *  y de propagación. Si es false, el listener del evento solo se activará durante las
    *  fases de destino y de propagación
    *
    * 1. La fase de captura (capture phase): el evento se envía a los ancestros del destino desde la raíz del árbol al padre directo del nodo de destino.
    * 2. La fase de destino (target phase): el evento se envía al nodo de destino.
    * 3. La fase de propagación (bubbling phase): el evento se envía a los antepasados ​​del destino desde el padre directo del nodo de destino a la raíz del árbol.
    */
   #event(type, listener, useCapture) {
      this.search.addEventListener(type, listener, useCapture);
   }

   /**
    * Crea un elemento a partir de la etiqueta que se desea
    * y devuelve ese mismo elemento.
    * @param {string} tag Etiqueta HTML
    * @param {string} className Asignacion de clase CSS
    * @returns Elemento HTML
    */
   #createElement(tag, className) {
      const element = document.createElement(tag);
      if (className) element.classList.add(className);
      return element;
   }

   /**
    * Elemento item de this.menu que tiene como parametro
    * una cadena de texto que sera desplegado como mensaje
    * @param {string} message cadena de texto como mensaje
    * @returns {HTMLElement} Elemento boton
    */
   #message(message) {
      const item = this.#createElement('li', 'menu-item');
      const button = this.#createElement('button', 'btn-menu-item');
      button.style.pointerEvents = 'none';
      button.style.textAlign = 'center';
      button.innerHTML = message;
      button.disabled = true;
      item.append(button);
      return item;
   }

   /**
    * Agrega y retorna un menu item para el search
    * @param {string} text Cadena de texto que contendra cada menu item
    * @param {string} typeButton Etiqueta <a> o <button> por defecto es <a>
    * @returns Elemento HTML
    */
   #item(text, typeButton = 'a') {
      const item = this.#createElement('li', 'menu-item');
      item.setAttribute('role', 'option');
      item.setAttribute('id', `option-${this.#itemIndex++}`);

      const button = this.#createElement(typeButton, 'btn-menu-item');
      button.innerHTML = text;
      button.addEventListener('mousedown', (evt) => {
         evt.preventDefault();
         const eventType = evt.buttons;
         if (eventType === 1) {
            this.search.value = button.textContent;
            this.listbox.style.display = 'none';
         }
      }, false);

      item.append(button);
      return item;
   }

   /**
    * Evento por defecto input del
    * search modo campos de busqueda
    */
   #onInputSearchDefault() {
      this.#event('input', (evt) => {
         evt.preventDefault();

         if (!evt.target.value) {
            this.#dataLength = 0;
            this.listbox.style.display = 'none';
         }
      }, {
         capture: true
      });
   }

   /**
    * Evento por defecto del search focusout.
    */
   #onFocusOutDefault() {
      this.#event('focusout', (evt) => {
         evt.preventDefault();

         this.#optionIndex = 0;
         this.listbox.style.display = 'none';

         this.#buttonItems.forEach((button) => {
            button.classList.remove('item-active');
         });
      }, false);
   }

   /**
    * Evento por defecto focusin del search.
    */
   #onFocusInDefault() {
      this.#event('focusin', (evt) => {
         evt.preventDefault();

         this.search.select();
         this.#options = this.menu.children;

         if (this.#mouseClickType === 1 || this.#keyboardCode == 'Tab') {
            this.#buttonItems = this.menu.querySelectorAll('[class=btn-menu-item]');
         }

      }, false);
   }

   /**
    * Evento por defecto de window resize.
    */
   #onWindowResizeDefault() {
      window.addEventListener('resize', (evt) => {
         this.listbox.style.left
            = (this.search.offsetLeft
               - this.search.scrollLeft
               + this.search.clientLeft)
            + "px";
         this.listbox.style.width
            = ((this.search.clientWidth - 1) + "px");
      }, false);
   }

   /**
    * Evento por defecto de window load.
    */
   #onWindowLoadDefault() {
      window.addEventListener('load', () => {
         this.listbox.style.left
            = (this.search.offsetLeft
               - this.search.scrollLeft
               + this.search.clientLeft)
            + "px";
         this.listbox.style.width
            = ((this.search.clientWidth - 1) + "px");
      }, false);
   }

   /**
    * Evento por defecto keydown del search.
    */
   #onKeydownDefualt() {
      this.#event('keydown', (evt) => {

         try {

            if (evt.code == 'Tab') {
               this.listbox.style.display = 'none';

               return;
            }

            if (evt.code == 'ArrowUp' && this.#dataLength > 0) {

               evt.preventDefault();

               if (this.listbox.style.display != 'none') {
                  this.#optionIndex = this.#optionIndex - 1;
               } else {
                  this.#optionIndex = this.#optionIndex;
               }

               if (this.#optionIndex < 1) {
                  this.#optionIndex = this.#dataLength;
               }

               this.search.setAttribute('aria-activedescendant',
                  `option-${this.#optionIndex}`);

               this.#buttonItems.forEach((button) => {
                  button.classList.remove('item-active');
               });

               this.#options[this.#optionIndex - 1]
                  .firstElementChild
                  .classList
                  .add('item-active');

               return;
            }

            if (evt.code == 'ArrowDown' && this.#dataLength > 0) {

               evt.preventDefault();

               if (this.listbox.style.display != 'none') {
                  this.#optionIndex = this.#optionIndex + 1;
               }

               if (
                  this.#optionIndex > this.#dataLength ||
                  (this.#optionIndex) <= 0
               ) {
                  this.#optionIndex = 1;
               }

               this.search.setAttribute('aria-activedescendant',
                  `option-${this.#optionIndex}`);

               this.#buttonItems.forEach((button) => {
                  button.classList.remove('item-active');
               });

               this.#options[(this.#optionIndex - 1)]
                  .firstElementChild
                  .classList
                  .add('item-active');

               return;
            }

            if (evt.code == 'Enter' && this.#dataLength > 0
               && (this.#optionIndex - 1) > -1) {

               evt.preventDefault();

               this.search.value = this.#options[this.#optionIndex - 1]
                  .firstElementChild
                  .textContent;

               this.listbox.style.display = 'none';

               return;
            }

            if (evt.code == 'Enter') {
               evt.preventDefault();
               return;
            }

         } catch (error) {
            console.error(error);
         }
      }, { capture: false });
   }

   /**
   * Evento por defecto de document keydown.
   */
   #onDocumentKeydownDefualt() {
      document.addEventListener('keydown', (evt) => {
         this.#keyboardCode = evt.code;
      }, false);
   }

   /**
    * Evento por defecto mousedown del search.
    */
   #onMouseDownDefault() {
      this.#event('mousedown', (evt) => {
         this.#mouseClickType = evt.buttons;
      }, false);
   }

   /**
    * Agrega un MenuItems al Search
    * al final del arreglo del listbox.
    * Ejemplo: search.add({ text: 'Hola Mundo' });
    * @param {Object} object Objeto del texto a agregar.
    */
   add(object) {
      this.data.push(object);
      return this;
   }

   /**
    * Remueve todos los elementos hijos de this.menu
    */
   clear() {
      this.data = [];
      while (this.menu.firstChild) {
         this.menu.firstChild.remove();
      }
   }

   /**
    * Dibuja en pantalla y agrega la lista de opciones al listbox
    */
   draw() {
      this.#itemIndex = 1;
      this.#optionIndex = 0;
      this.#dataLength = this.data.length;

      if (this.#dataLength <= 0) {
         this.menu.append(this.#message('⚠️ Dato no encontrado!'));
      } else if (this.#dataLength > 0) {
         this.data.forEach(item => {
            this.menu.append(this.#item(item.text));
         });
      }

      if (this.search.value) {
         this.listbox.style.display = 'block';
      }

      this.#buttonItems
         = this.menu.querySelectorAll('[class=btn-menu-item]');
   }

   /**
    * Inhabilita el search si es verdadero, de lo contrario
    * los respectivos elementos seran habilitado.
    * @param {boolean} isDisabled Verdadero si son inhabilitado, Falso si son habilitados
    */
   disable(isDisabled) {
      if (isDisabled) {
         this.search.setAttribute('disabled', isDisabled);
      } else {
         this.search.removeAttribute('disabled');
      }
      return this;
   }

}

/*-----------------------------------------------------
 *                     Spinners
 *-----------------------------------------------------*/
/**
 * Clase Spinnner personalizado, agrega propiedades
 * a un spin.
 */
export class Spinner {

   /**
    * Constructor de la clase Spinner.
    * @param {string} spin Entrada de texto formateado para numeros enteros y flotantes.
    * @param {string} btnUp Botón de arriba.
    * @param {string} btnDown Botón de abajo.
    */
   constructor(spin, btnUp, btnDown) {
      this.spin = document.getElementById(spin);
      this.btnUp = document.getElementById(btnUp);
      this.btnDown = document.getElementById(btnDown);
      this.#bind();
   }

   /**
    * Enlaza las propiedades del input
    * spin con el boton de arriba y de abajo.
    */
   #bind() {
      // Eventos click del botón de arriba y abajo
      this.btnUp.addEventListener('click', (evt) => {
         evt.preventDefault();
         if (this.spin == document.activeElement)
            this.spin.stepUp();
      }, false);

      this.btnDown.addEventListener('click', (evt) => {
         evt.preventDefault();
         if (this.spin == document.activeElement)
            this.spin.stepDown();
      }, false);

      // Eventos de enfoque del boton de arriba
      this.btnUp.addEventListener('focusin', (evt) => {
         evt.preventDefault();
         this.spin.focus();
         this.#borderFocusButtons('#4A4C7D');
      }, false);

      this.btnUp.addEventListener('focusout', (evt) => {
         evt.preventDefault();
         this.spin.focus();
         this.#borderFocusButtons('#dbdbdb');
      }, false);

      // Eventos de enfoque del boton de abajo
      this.btnDown.addEventListener('focusin', (evt) => {
         evt.preventDefault();
         this.spin.focus();
         this.#borderFocusButtons('#4A4C7D');
      }, false);

      this.btnDown.addEventListener('focusout', (evt) => {
         evt.preventDefault();
         this.spin.focus();
         this.#borderFocusButtons('#dbdbdb');
      }, false);

      // Evento de enfoque de input spin
      this.spin.addEventListener('focusin', (evt) => {
         evt.preventDefault();
         this.#borderFocusButtons('#4A4C7D');
      }, false);

      this.spin.addEventListener('focusout', (evt) => {
         evt.preventDefault();
         this.#borderFocusButtons('#dbdbdb');
      }, false);
   }

   /**
    * Establece de forma generica el color de los bordes
    * de los botones de arriba y abajo del componente spin.
    * @param {*} color Color en cualquier formato RGB, RGBA, HEX...
    */
   #borderFocusButtons(color) {
      this.btnUp.style.border = `1px solid ${color}`;
      this.btnDown.style.borderLeft = `1px solid ${color}`;
      this.btnDown.style.borderRight = `1px solid ${color}`;
      this.btnDown.style.borderBottom = `1px solid ${color}`;
   }

   /**
    * Inhabilita al spinner y los botones del mismo cuando
    * es establecido como verdadero, de lo contrario
    * los respectivos elementos seran habilitado.
    * @param {*} isDisabled Verdadero si son inhabilitado, Falso si son habilitados
    */
   disable(isDisabled) {
      if (isDisabled) {
         this.spin.setAttribute('disabled', isDisabled);
         this.btnUp.setAttribute('disabled', isDisabled);
         this.btnDown.setAttribute('disabled', isDisabled);
         this.btnUp.style.pointerEvents = 'none';
         this.btnDown.style.pointerEvents = 'none';
      } else {
         this.spin.removeAttribute('disabled');
         this.btnUp.removeAttribute('disabled');
         this.btnDown.removeAttribute('disabled');
         this.btnUp.style.pointerEvents = 'full';
         this.btnDown.style.pointerEvents = 'full';
      }
   }

   /**
    * Cambia al spinner en modo edicion
    * cuando se establece como Verdadero,
    * de lo contrario en modo solo lectura (no editable)
    * @param {*} isEditable Verdadero si es editable, Falso si no es editable
    */
   editable(isEditable) {
      if (isEditable) {
         this.spin.removeAttribute('readonly');
      } else {
         this.spin.setAttribute('readonly', isEditable);
      }
   }
}

window.addEventListener('load', modal, false);
window.addEventListener('load', popover, false);
window.addEventListener('load', select, false);
window.addEventListener('load', tooltip, false);