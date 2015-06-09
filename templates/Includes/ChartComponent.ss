<div class="chart-wrapper chart-type-$ChartType $EvenOdd">
    <% if Odd %>
        <% cached $ChartCacheKey %>
        <div class="chart-visual">
            <canvas id="$ChartType-$ID" class="chart" data-json="$ChartData"></canvas>
        </div>
        <% end_cached %>
        <div class="chart-text">
            <h2>$Title</h2>
            <div>$Description</div>
        </div>
    <% else %>
        <div class="chart-text">
            <h2>$Title</h2>
            <div>$Description</div>
        </div>
        <% cached $ChartCacheKey %>
        <div class="chart-visual">
            <canvas id="$ChartType-$ID" class="chart" data-json="$ChartData"></canvas>
        </div>
        <% end_cached %>
    <% end_if %>
</div>
