(self["webpackChunk"] = self["webpackChunk"] || []).push([["collections"],{

/***/ "./assets/collections.js":
/*!*******************************!*\
  !*** ./assets/collections.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

/* provided dependency */ var $ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* provided dependency */ var jQuery = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
__webpack_require__(/*! core-js/modules/es.string.replace.js */ "./node_modules/core-js/modules/es.string.replace.js");

__webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");

__webpack_require__(/*! core-js/modules/es.array.find.js */ "./node_modules/core-js/modules/es.array.find.js");

function addFormToCollection($collectionHolderClass) {
  // Get the ul that holds the collection of tags
  var $collectionHolder = $('.' + $collectionHolderClass); // Get the data-prototype explained earlier

  var prototype = $collectionHolder.data('prototype'); // get the new index

  var index = $collectionHolder.data('index');
  var newForm = prototype; // You need this only if you didn't set 'label' => false in your tags field in TaskType
  // Replace '__name__label__' in the prototype's HTML to
  // instead be a number based on how many items we have
  // newForm = newForm.replace(/__name__label__/g, index);
  // Replace '__name__' in the prototype's HTML to
  // instead be a number based on how many items we have

  newForm = newForm.replace(/__name__/g, index); // increase the index with one for the next item

  $collectionHolder.data('index', index + 1); // Display the form in the page in an li, before the "Add a tag" link li

  var $newFormLi = $('<div class="card border-primary" style="margin-bottom:10px"><div class="card-body"></div></div>').append(newForm); // Add the new form at the end of the list

  $collectionHolder.append($newFormLi); //Para el remove

  addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
  var $removeFormButton = $('<button type="button" class="btn btn-secondary">Eliminar dispositivo</button>');
  $tagFormLi.find('div.card-body').append($removeFormButton);
  $removeFormButton.on('click', function (e) {
    // remove the li for the tag form
    $tagFormLi.remove();
  });
}

jQuery(document).ready(function () {
  /**********************************************************/

  /*           ADD                                          */

  /**********************************************************/
  // Get the ul that holds the collection of tags
  var $usuarioDispositivoCollectionHolder = $('div.usuarioDispositivo'); // count the current form inputs we have (e.g. 2), use that as the new
  // index when inserting a new item (e.g. 2)

  $usuarioDispositivoCollectionHolder.data('index', $usuarioDispositivoCollectionHolder.find('input').length);
  $('body').on('click', '.add_item_link', function (e) {
    var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass'); // add a new tag form (see next code block)

    addFormToCollection($collectionHolderClass);
  });
  /**********************************************************/

  /*           REMOVE                                       */

  /**********************************************************/

  var $collectionHolder = $('div.usuarioDispositivo'); // add a delete link to all of the existing tag form li elements

  $collectionHolder.find('div.card').each(function () {
    addTagFormDeleteLink($(this));
  });
});

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_core-js_internals_add-to-unscopables_js-node_modules_core-js_internals_a-7b8321","vendors-node_modules_core-js_modules_es_array_find_js-node_modules_core-js_modules_es_string_-b9e064"], () => (__webpack_exec__("./assets/collections.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiY29sbGVjdGlvbnMuanMiLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7OztBQUFBLFNBQVNBLG1CQUFULENBQTZCQyxzQkFBN0IsRUFBcUQ7QUFDakQ7QUFFQSxNQUFJQyxpQkFBaUIsR0FBR0MsQ0FBQyxDQUFDLE1BQU1GLHNCQUFQLENBQXpCLENBSGlELENBS2pEOztBQUNBLE1BQUlHLFNBQVMsR0FBR0YsaUJBQWlCLENBQUNHLElBQWxCLENBQXVCLFdBQXZCLENBQWhCLENBTmlELENBUWpEOztBQUNBLE1BQUlDLEtBQUssR0FBR0osaUJBQWlCLENBQUNHLElBQWxCLENBQXVCLE9BQXZCLENBQVo7QUFFQSxNQUFJRSxPQUFPLEdBQUdILFNBQWQsQ0FYaUQsQ0FhakQ7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBOztBQUNBRyxFQUFBQSxPQUFPLEdBQUdBLE9BQU8sQ0FBQ0MsT0FBUixDQUFnQixXQUFoQixFQUE2QkYsS0FBN0IsQ0FBVixDQXBCaUQsQ0FzQmpEOztBQUNBSixFQUFBQSxpQkFBaUIsQ0FBQ0csSUFBbEIsQ0FBdUIsT0FBdkIsRUFBZ0NDLEtBQUssR0FBRyxDQUF4QyxFQXZCaUQsQ0F5QmpEOztBQUNBLE1BQUlHLFVBQVUsR0FBR04sQ0FBQyxDQUFDLGlHQUFELENBQUQsQ0FBcUdPLE1BQXJHLENBQTRHSCxPQUE1RyxDQUFqQixDQTFCaUQsQ0EyQmpEOztBQUNBTCxFQUFBQSxpQkFBaUIsQ0FBQ1EsTUFBbEIsQ0FBeUJELFVBQXpCLEVBNUJpRCxDQThCakQ7O0FBQ0FFLEVBQUFBLG9CQUFvQixDQUFDRixVQUFELENBQXBCO0FBRUg7O0FBR0QsU0FBU0Usb0JBQVQsQ0FBOEJDLFVBQTlCLEVBQTBDO0FBQ3RDLE1BQUlDLGlCQUFpQixHQUFHVixDQUFDLENBQUMsK0VBQUQsQ0FBekI7QUFDQVMsRUFBQUEsVUFBVSxDQUFDRSxJQUFYLENBQWdCLGVBQWhCLEVBQWlDSixNQUFqQyxDQUF3Q0csaUJBQXhDO0FBRUFBLEVBQUFBLGlCQUFpQixDQUFDRSxFQUFsQixDQUFxQixPQUFyQixFQUE4QixVQUFTQyxDQUFULEVBQVk7QUFDdEM7QUFDQUosSUFBQUEsVUFBVSxDQUFDSyxNQUFYO0FBQ0gsR0FIRDtBQUlIOztBQUdEQyxNQUFNLENBQUNDLFFBQUQsQ0FBTixDQUFpQkMsS0FBakIsQ0FBdUIsWUFBVztBQUU5Qjs7QUFDQTs7QUFDQTtBQUVBO0FBQ0EsTUFBSUMsbUNBQW1DLEdBQUdsQixDQUFDLENBQUMsd0JBQUQsQ0FBM0MsQ0FQOEIsQ0FTOUI7QUFDQTs7QUFDQWtCLEVBQUFBLG1DQUFtQyxDQUFDaEIsSUFBcEMsQ0FBeUMsT0FBekMsRUFBa0RnQixtQ0FBbUMsQ0FBQ1AsSUFBcEMsQ0FBeUMsT0FBekMsRUFBa0RRLE1BQXBHO0FBRUFuQixFQUFBQSxDQUFDLENBQUMsTUFBRCxDQUFELENBQVVZLEVBQVYsQ0FBYSxPQUFiLEVBQXNCLGdCQUF0QixFQUF3QyxVQUFTQyxDQUFULEVBQVk7QUFDaEQsUUFBSWYsc0JBQXNCLEdBQUdFLENBQUMsQ0FBQ2EsQ0FBQyxDQUFDTyxhQUFILENBQUQsQ0FBbUJsQixJQUFuQixDQUF3Qix1QkFBeEIsQ0FBN0IsQ0FEZ0QsQ0FFaEQ7O0FBQ0FMLElBQUFBLG1CQUFtQixDQUFDQyxzQkFBRCxDQUFuQjtBQUNILEdBSkQ7QUFNQTs7QUFDQTs7QUFDQTs7QUFDQSxNQUFJQyxpQkFBaUIsR0FBR0MsQ0FBQyxDQUFDLHdCQUFELENBQXpCLENBdEI4QixDQXVCOUI7O0FBQ0FELEVBQUFBLGlCQUFpQixDQUFDWSxJQUFsQixDQUF1QixVQUF2QixFQUFtQ1UsSUFBbkMsQ0FBd0MsWUFBVztBQUMvQ2IsSUFBQUEsb0JBQW9CLENBQUNSLENBQUMsQ0FBQyxJQUFELENBQUYsQ0FBcEI7QUFDSCxHQUZEO0FBT0gsQ0EvQkQiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvY29sbGVjdGlvbnMuanMiXSwic291cmNlc0NvbnRlbnQiOlsiZnVuY3Rpb24gYWRkRm9ybVRvQ29sbGVjdGlvbigkY29sbGVjdGlvbkhvbGRlckNsYXNzKSB7XG4gICAgLy8gR2V0IHRoZSB1bCB0aGF0IGhvbGRzIHRoZSBjb2xsZWN0aW9uIG9mIHRhZ3NcblxuICAgIHZhciAkY29sbGVjdGlvbkhvbGRlciA9ICQoJy4nICsgJGNvbGxlY3Rpb25Ib2xkZXJDbGFzcyk7XG5cbiAgICAvLyBHZXQgdGhlIGRhdGEtcHJvdG90eXBlIGV4cGxhaW5lZCBlYXJsaWVyXG4gICAgdmFyIHByb3RvdHlwZSA9ICRjb2xsZWN0aW9uSG9sZGVyLmRhdGEoJ3Byb3RvdHlwZScpO1xuXG4gICAgLy8gZ2V0IHRoZSBuZXcgaW5kZXhcbiAgICB2YXIgaW5kZXggPSAkY29sbGVjdGlvbkhvbGRlci5kYXRhKCdpbmRleCcpO1xuXG4gICAgdmFyIG5ld0Zvcm0gPSBwcm90b3R5cGU7XG5cbiAgICAvLyBZb3UgbmVlZCB0aGlzIG9ubHkgaWYgeW91IGRpZG4ndCBzZXQgJ2xhYmVsJyA9PiBmYWxzZSBpbiB5b3VyIHRhZ3MgZmllbGQgaW4gVGFza1R5cGVcbiAgICAvLyBSZXBsYWNlICdfX25hbWVfX2xhYmVsX18nIGluIHRoZSBwcm90b3R5cGUncyBIVE1MIHRvXG4gICAgLy8gaW5zdGVhZCBiZSBhIG51bWJlciBiYXNlZCBvbiBob3cgbWFueSBpdGVtcyB3ZSBoYXZlXG4gICAgLy8gbmV3Rm9ybSA9IG5ld0Zvcm0ucmVwbGFjZSgvX19uYW1lX19sYWJlbF9fL2csIGluZGV4KTtcblxuICAgIC8vIFJlcGxhY2UgJ19fbmFtZV9fJyBpbiB0aGUgcHJvdG90eXBlJ3MgSFRNTCB0b1xuICAgIC8vIGluc3RlYWQgYmUgYSBudW1iZXIgYmFzZWQgb24gaG93IG1hbnkgaXRlbXMgd2UgaGF2ZVxuICAgIG5ld0Zvcm0gPSBuZXdGb3JtLnJlcGxhY2UoL19fbmFtZV9fL2csIGluZGV4KTtcblxuICAgIC8vIGluY3JlYXNlIHRoZSBpbmRleCB3aXRoIG9uZSBmb3IgdGhlIG5leHQgaXRlbVxuICAgICRjb2xsZWN0aW9uSG9sZGVyLmRhdGEoJ2luZGV4JywgaW5kZXggKyAxKTtcblxuICAgIC8vIERpc3BsYXkgdGhlIGZvcm0gaW4gdGhlIHBhZ2UgaW4gYW4gbGksIGJlZm9yZSB0aGUgXCJBZGQgYSB0YWdcIiBsaW5rIGxpXG4gICAgdmFyICRuZXdGb3JtTGkgPSAkKCc8ZGl2IGNsYXNzPVwiY2FyZCBib3JkZXItcHJpbWFyeVwiIHN0eWxlPVwibWFyZ2luLWJvdHRvbToxMHB4XCI+PGRpdiBjbGFzcz1cImNhcmQtYm9keVwiPjwvZGl2PjwvZGl2PicpLmFwcGVuZChuZXdGb3JtKTtcbiAgICAvLyBBZGQgdGhlIG5ldyBmb3JtIGF0IHRoZSBlbmQgb2YgdGhlIGxpc3RcbiAgICAkY29sbGVjdGlvbkhvbGRlci5hcHBlbmQoJG5ld0Zvcm1MaSlcblxuICAgIC8vUGFyYSBlbCByZW1vdmVcbiAgICBhZGRUYWdGb3JtRGVsZXRlTGluaygkbmV3Rm9ybUxpKTtcblxufVxuXG5cbmZ1bmN0aW9uIGFkZFRhZ0Zvcm1EZWxldGVMaW5rKCR0YWdGb3JtTGkpIHtcbiAgICB2YXIgJHJlbW92ZUZvcm1CdXR0b24gPSAkKCc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImJ0biBidG4tc2Vjb25kYXJ5XCI+RWxpbWluYXIgZGlzcG9zaXRpdm88L2J1dHRvbj4nKTtcbiAgICAkdGFnRm9ybUxpLmZpbmQoJ2Rpdi5jYXJkLWJvZHknKS5hcHBlbmQoJHJlbW92ZUZvcm1CdXR0b24pO1xuXG4gICAgJHJlbW92ZUZvcm1CdXR0b24ub24oJ2NsaWNrJywgZnVuY3Rpb24oZSkge1xuICAgICAgICAvLyByZW1vdmUgdGhlIGxpIGZvciB0aGUgdGFnIGZvcm1cbiAgICAgICAgJHRhZ0Zvcm1MaS5yZW1vdmUoKTtcbiAgICB9KTtcbn1cblxuXG5qUXVlcnkoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuXG4gICAgLyoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKiovXG4gICAgLyogICAgICAgICAgIEFERCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICovXG4gICAgLyoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKiovXG5cbiAgICAvLyBHZXQgdGhlIHVsIHRoYXQgaG9sZHMgdGhlIGNvbGxlY3Rpb24gb2YgdGFnc1xuICAgIHZhciAkdXN1YXJpb0Rpc3Bvc2l0aXZvQ29sbGVjdGlvbkhvbGRlciA9ICQoJ2Rpdi51c3VhcmlvRGlzcG9zaXRpdm8nKTtcblxuICAgIC8vIGNvdW50IHRoZSBjdXJyZW50IGZvcm0gaW5wdXRzIHdlIGhhdmUgKGUuZy4gMiksIHVzZSB0aGF0IGFzIHRoZSBuZXdcbiAgICAvLyBpbmRleCB3aGVuIGluc2VydGluZyBhIG5ldyBpdGVtIChlLmcuIDIpXG4gICAgJHVzdWFyaW9EaXNwb3NpdGl2b0NvbGxlY3Rpb25Ib2xkZXIuZGF0YSgnaW5kZXgnLCAkdXN1YXJpb0Rpc3Bvc2l0aXZvQ29sbGVjdGlvbkhvbGRlci5maW5kKCdpbnB1dCcpLmxlbmd0aCk7XG5cbiAgICAkKCdib2R5Jykub24oJ2NsaWNrJywgJy5hZGRfaXRlbV9saW5rJywgZnVuY3Rpb24oZSkge1xuICAgICAgICB2YXIgJGNvbGxlY3Rpb25Ib2xkZXJDbGFzcyA9ICQoZS5jdXJyZW50VGFyZ2V0KS5kYXRhKCdjb2xsZWN0aW9uSG9sZGVyQ2xhc3MnKTtcbiAgICAgICAgLy8gYWRkIGEgbmV3IHRhZyBmb3JtIChzZWUgbmV4dCBjb2RlIGJsb2NrKVxuICAgICAgICBhZGRGb3JtVG9Db2xsZWN0aW9uKCRjb2xsZWN0aW9uSG9sZGVyQ2xhc3MpO1xuICAgIH0pXG5cbiAgICAvKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi9cbiAgICAvKiAgICAgICAgICAgUkVNT1ZFICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgKi9cbiAgICAvKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi9cbiAgICB2YXIgJGNvbGxlY3Rpb25Ib2xkZXIgPSAkKCdkaXYudXN1YXJpb0Rpc3Bvc2l0aXZvJyk7XG4gICAgLy8gYWRkIGEgZGVsZXRlIGxpbmsgdG8gYWxsIG9mIHRoZSBleGlzdGluZyB0YWcgZm9ybSBsaSBlbGVtZW50c1xuICAgICRjb2xsZWN0aW9uSG9sZGVyLmZpbmQoJ2Rpdi5jYXJkJykuZWFjaChmdW5jdGlvbigpIHtcbiAgICAgICAgYWRkVGFnRm9ybURlbGV0ZUxpbmsoJCh0aGlzKSk7XG4gICAgfSk7XG5cblxuXG4gICAgXG59KTtcblxuXG4iXSwibmFtZXMiOlsiYWRkRm9ybVRvQ29sbGVjdGlvbiIsIiRjb2xsZWN0aW9uSG9sZGVyQ2xhc3MiLCIkY29sbGVjdGlvbkhvbGRlciIsIiQiLCJwcm90b3R5cGUiLCJkYXRhIiwiaW5kZXgiLCJuZXdGb3JtIiwicmVwbGFjZSIsIiRuZXdGb3JtTGkiLCJhcHBlbmQiLCJhZGRUYWdGb3JtRGVsZXRlTGluayIsIiR0YWdGb3JtTGkiLCIkcmVtb3ZlRm9ybUJ1dHRvbiIsImZpbmQiLCJvbiIsImUiLCJyZW1vdmUiLCJqUXVlcnkiLCJkb2N1bWVudCIsInJlYWR5IiwiJHVzdWFyaW9EaXNwb3NpdGl2b0NvbGxlY3Rpb25Ib2xkZXIiLCJsZW5ndGgiLCJjdXJyZW50VGFyZ2V0IiwiZWFjaCJdLCJzb3VyY2VSb290IjoiIn0=