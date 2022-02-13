(function ($, Drupal) {
  'use strict';

  Drupal.search_beverage = Drupal.search_beverage || {};

  /**
   * Holds the form data for results rendering.
   */
  Drupal.search_beverage.data = {
    items: {},
    items_per_page: drupalSettings.search_beverage.items_per_page,
    search_term: '',
    page: 0
  };

  /**
   * Initialite search results container.
   */
  Drupal.search_beverage.init = function() {
    $('.search-results-container nav a').on('click', function (event) {
      event.preventDefault();
      let url = $(this).attr('href');
      let urlParams = Drupal.search_beverage.parseQueryParams(url);
      let data = Drupal.search_beverage.data;
      if (urlParams.page) {
        data.page = urlParams.page;
      }
      Drupal.search_beverage.renderResults(data);
    });
  };

  /**
   * Render search results.
   *
   * @param {Object} data
   *   Data used to render search results.
   */
  Drupal.search_beverage.renderResults = function (data) {
    let url = drupalSettings.search_beverage.search_results_path + '?page=' + data.page;
    $.post(url, data, function (response) {
      $('.search-results-container').html(response);
      Drupal.search_beverage.init();
    });
  }

  /**
   * Implementation of AJAX command.
   *
   * Fetch results from search API and pass them to Drupal for rendering.
   *
   * @param ajax
   * @param response
   *   AJAX response object.
   * @param status
   *   Status name.
   */
  Drupal.AjaxCommands.prototype.searchFormSubmit = function (ajax, response, status) {
    let data = Drupal.search_beverage.data;
    let searchTerm = response.options.search_term;
    let newRequest = (data.search_term !== searchTerm);

    data.search_term = response.options.search_term;

    // Perform API request only for the first time.
    if (newRequest && searchTerm) {
      $.ajax({
        url: drupalSettings.search_beverage.endpoint,
        dataType: "jsonp",
        data: {query: searchTerm},
      }).done(function(response){
        data.page = 0;
        data.items = {};
        for (let i = 0; i < response.items.length; i++) {
          data.items[i] = {
            name: response.items[i].name,
            producer: response.items[i].producer,
            country: response.items[i].country,
            type: response.items[i].type,
          };
        }

        Drupal.search_beverage.renderResults(data);
      });
    }
    else {
      Drupal.search_beverage.renderResults(data);
    }
  };

  /**
   * Parse query parameters from URL string.
   *
   * @param {string} url
   *   URL string for parsing.
   *
   * @return {Object}
   *   List of query params.
   */
  Drupal.search_beverage.parseQueryParams = function (url) {
    let queryParams = {};
    var queryString = url.substring(url.indexOf('?') + 1).split('&');
    for (var i = 0; i < queryString.length; i++) {
      queryString[i] = queryString[i].split('=');
      queryParams[queryString[i][0]] = decodeURIComponent(queryString[i][1]);
    }
    return queryParams;
  };

}(jQuery, Drupal, drupalSettings));
