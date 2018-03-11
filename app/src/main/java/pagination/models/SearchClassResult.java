package pagination.models;

import java.util.ArrayList;
import java.util.List;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

/**
 * Created by peo5032 on 3/7/18.
 */

public class SearchClassResult<T> {



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
        private List<T> results = null;

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

        public List<T> getDetailedObjects() {
            return results;
        }

        public void setResults(List<T> results) {
            this.results = results;
        }
    @Override
    public String toString(){
        String retVal =  "currentPage: " + getCurrentPage() +
                " totalResults: " + getTotalResults() +
                " totalPages: " + getTotalPages() + "\n";

        StringBuilder stringBuilder = new StringBuilder(retVal);
        if(results != null){
            for(T el: results){
                stringBuilder.append(el);
            }
        }
        return stringBuilder.toString();

    }
}
