
package pagination.models;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

import java.util.List;

public class ClassListResult<T> {

    @SerializedName("currentPage")
    @Expose
    private Integer currentPage;
    @SerializedName("totalResults")
    @Expose
    private Integer totalResults;
    @SerializedName("totalPages")
    @Expose
    private Integer totalPages;
    @SerializedName("results")
    @Expose
    private List<T> results;

    public Integer getCurrentPage() {
        return currentPage;
    }

    public void setCurrentPage(Integer currentPage) {
        this.currentPage = currentPage;
    }

    public Integer getTotalResults() {
        return totalResults;
    }

    public void setTotalResults(Integer totalResults) {
        this.totalResults = totalResults;
    }

    public Integer getTotalPages() {
        return totalPages;
    }

    public void setTotalPages(Integer totalPages) {
        this.totalPages = totalPages;
    }

    public List<T> getResults() {
        return results;
    }

    public void setResults(List<T> results) {
        this.results = results;
    }

    @Override
    public String toString(){
        String retVal =  "currentPage: " + getCurrentPage() +
                ", totalResults: " + getTotalResults() +
                ", totalPages: " + getTotalPages() + "\n";

        StringBuilder stringBuilder = new StringBuilder(retVal);
        if(results != null){
            int i = 0;
            for(T el: results){
                stringBuilder.append("("+ (i++) + "): ");
                stringBuilder.append(el);
                stringBuilder.append("\n");
            }
        }
        return stringBuilder.toString();
    }
}