<div class="chart-wrapper">
    <% cached $ChartCacheKey %>
    <div class="chart-visual">
        <canvas id="$ChartType-$ID" class="chart-type-$ChartType" data-json="$ChartData"></canvas>
    </div>
    <% end_cached %>
    <div class="chart-text">
        <h2>$Title</h2>
        <div>$Description</div>
    </div>
</div>
