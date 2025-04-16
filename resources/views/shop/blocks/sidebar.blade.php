<div class="d-none d-xl-block col-xl-3 col-wd-2gdot5">
    <div class="mb-6 border border-width-2 border-color-3 borders-radius-6">
        <!-- List -->
        <ul id="sidebarNav" class="list-unstyled mb-0 sidebar-navbar view-all">
            <li><div class="dropdown-title">List Categories</div></li>

            @foreach($categories as $category)
            <li>
                <a href="{{route('category.products', $category->id)}}" >
                    {{ $category->name }}
                </a>
            </li>
            @endforeach
        </ul>
        <!-- End List -->
    </div>
    <div class="mb-6">
        <div class="border-bottom border-color-1 mb-5">
            <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Filters</h3>
        </div>
        <div class="border-bottom pb-4 mb-4">
            <h4 class="font-size-14 mb-3 font-weight-bold">Brands</h4>

            <!-- Checkboxes -->
            <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="brandAdidas">
                    <label class="custom-control-label" for="brandAdidas">Adidas
                        <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span>
                    </label>
                </div>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="brandNewBalance">
                    <label class="custom-control-label" for="brandNewBalance">New Balance
                        <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span>
                    </label>
                </div>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="brandNike">
                    <label class="custom-control-label" for="brandNike">Nike
                        <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span>
                    </label>
                </div>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="brandFredPerry">
                    <label class="custom-control-label" for="brandFredPerry">Fred Perry
                        <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span>
                    </label>
                </div>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="brandTnf">
                    <label class="custom-control-label" for="brandTnf">The North Face
                        <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span>
                    </label>
                </div>
            </div>
            <!-- End Checkboxes -->

            <!-- View More - Collapse -->
            <div class="collapse" id="collapseBrand">
                <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="brandGucci">
                        <label class="custom-control-label" for="brandGucci">Gucci
                            <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span>
                        </label>
                    </div>
                </div>
                <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="brandMango">
                        <label class="custom-control-label" for="brandMango">Mango
                            <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- End View More - Collapse -->

            <!-- Link -->
            <a class="link link-collapse small font-size-13 text-gray-27 d-inline-flex mt-2" data-toggle="collapse" href="#collapseBrand" role="button" aria-expanded="false" aria-controls="collapseBrand">
                <span class="link__icon text-gray-27 bg-white">
                    <span class="link__icon-inner">+</span>
                </span>
                <span class="link-collapse__default">Show more</span>
                <span class="link-collapse__active">Show less</span>
            </a>
            <!-- End Link -->
        </div>
        <div class="border-bottom pb-4 mb-4">
            <h4 class="font-size-14 mb-3 font-weight-bold">Color</h4>

            <!-- Checkboxes -->
            <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="categoryTshirt">
                    <label class="custom-control-label" for="categoryTshirt">Black <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span></label>
                </div>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="categoryShoes">
                    <label class="custom-control-label" for="categoryShoes">Black Leather <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span></label>
                </div>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="categoryAccessories">
                    <label class="custom-control-label" for="categoryAccessories">Black with Red <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span></label>
                </div>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="categoryTops">
                    <label class="custom-control-label" for="categoryTops">Gold <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span></label>
                </div>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="categoryBottom">
                    <label class="custom-control-label" for="categoryBottom">Spacegrey <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span></label>
                </div>
            </div>
            <!-- End Checkboxes -->

            <!-- View More - Collapse -->
            <div class="collapse" id="collapseColor">
                <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="categoryShorts">
                        <label class="custom-control-label" for="categoryShorts">Turquoise <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span></label>
                    </div>
                </div>
                <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="categoryHats">
                        <label class="custom-control-label" for="categoryHats">White <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span></label>
                    </div>
                </div>
                <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="categorySocks">
                        <label class="custom-control-label" for="categorySocks">White with Gold <span class="text-gray-25 font-size-12 font-weight-normal"> (56)</span></label>
                    </div>
                </div>
            </div>
            <!-- End View More - Collapse -->

            <!-- Link -->
            <a class="link link-collapse small font-size-13 text-gray-27 d-inline-flex mt-2" data-toggle="collapse" href="#collapseColor" role="button" aria-expanded="false" aria-controls="collapseColor">
                <span class="link__icon text-gray-27 bg-white">
                    <span class="link__icon-inner">+</span>
                </span>
                <span class="link-collapse__default">Show more</span>
                <span class="link-collapse__active">Show less</span>
            </a>
            <!-- End Link -->
        </div>
        <div class="range-slider">
            <h4 class="font-size-14 mb-3 font-weight-bold">Price</h4>
            <!-- Range Slider -->
            <input class="js-range-slider" type="text"
            data-extra-classes="u-range-slider u-range-slider-indicator u-range-slider-grid"
            data-type="double"
            data-grid="false"
            data-hide-from-to="true"
            data-prefix="$"
            data-min="0"
            data-max="3456"
            data-from="0"
            data-to="3456"
            data-result-min="#rangeSliderExample3MinResult"
            data-result-max="#rangeSliderExample3MaxResult">
            <!-- End Range Slider -->
            <div class="mt-1 text-gray-111 d-flex mb-4">
                <span class="mr-0dot5">Price: </span>
                <span>$</span>
                <span id="rangeSliderExample3MinResult" class=""></span>
                <span class="mx-0dot5"> — </span>
                <span>$</span>
                <span id="rangeSliderExample3MaxResult" class=""></span>
            </div>
            <button type="submit" class="btn px-4 btn-primary-dark-w py-2 rounded-lg">Filter</button>
        </div>
    </div>
</div>
