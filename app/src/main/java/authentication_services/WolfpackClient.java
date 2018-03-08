package authentication_services;


import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import okhttp3.OkHttpClient;
import okhttp3.ResponseBody;
import okhttp3.logging.HttpLoggingInterceptor;
import pagination.models.SearchClassResult;
import pagination.models.SearchResultSection;
import retrofit2.Call;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;

public interface WolfpackClient{

    //local testing for pabz
    String BASE_URL = "http://192.168.1.57";

    String FEED = "/pages/androidAPI.php";
    //String FEED = "/pages/test.php";
    //String FEED = "pages/Sign_in_student.php";

    //wolfpack VM location
    //String FEED = "pablo_test_android/Sign_up.php";
    //String BASE_URL = "http://wolfpack.cs.hbg.psu.edu/";
    String LOGIN = "pablo_test_android/Sign_in_student.php";

    Gson gson = new GsonBuilder()
            .setLenient()
            .create();

    HttpLoggingInterceptor interceptor = new HttpLoggingInterceptor()
            .setLevel(HttpLoggingInterceptor.Level.BODY);

    OkHttpClient client = new OkHttpClient.Builder()
            .addInterceptor(interceptor).build();

    Retrofit otherRetrofit = new Retrofit.Builder()
            .client(client)
            .baseUrl(BASE_URL)
            .build();

    Retrofit retrofit = new Retrofit.Builder()
            .client(client)
            .baseUrl(BASE_URL)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build();

    @FormUrlEncoded
    @POST(FEED)
    Call<LoginDetails> attemptLogin(
            @Field("android") Boolean isAndroid,
            @Field("inputMethodName") String methodName,
            @Field("inputEmail") String email,
            @Field("inputPassword") String password
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<LoginDetails> attemptSignUp(
            @Field("inputFirstName") String first_name,
            @Field("inputLastName") String last_name,
            @Field("inputEmail") String email,
            @Field("inputPassword") String password
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<SearchClassResult<SearchResultSection>> findClasses(
            @Field("inputCurrentPageNumber") int currentPage,
            @Field("inputClassTitle") String classTitle
    );

}